import Foundation
import UIKit
import Capacitor
import GoogleSignIn

/// Plugin nativo de Google Sign-In para iOS.
/// Se expone a JavaScript con el nombre "GoogleAuth" y devuelve los mismos campos
/// que @codetrix-studio/capacitor-google-auth, para que el código web existente
/// (doNativeCapacitorGoogleAuth en login/register) funcione sin cambios.
///
/// El Client ID de iOS se lee de Info.plist (clave GIDClientID).
@objc(ViantrypGoogleSigninPlugin)
public class ViantrypGoogleSigninPlugin: CAPPlugin, CAPBridgedPlugin {
    public let identifier = "ViantrypGoogleSigninPlugin"
    public let jsName = "GoogleAuth"
    public let pluginMethods: [CAPPluginMethod] = [
        CAPPluginMethod(name: "initialize", returnType: CAPPluginReturnPromise),
        CAPPluginMethod(name: "signIn", returnType: CAPPluginReturnPromise),
        CAPPluginMethod(name: "signOut", returnType: CAPPluginReturnPromise)
    ]

    private func iosClientId() -> String? {
        if let id = Bundle.main.object(forInfoDictionaryKey: "GIDClientID") as? String, !id.isEmpty {
            return id
        }
        return nil
    }

    private func configure() -> Bool {
        guard let clientId = iosClientId() else { return false }
        GIDSignIn.sharedInstance.configuration = GIDConfiguration(clientID: clientId)
        return true
    }

    @objc func initialize(_ call: CAPPluginCall) {
        // El clientId que manda la web es el de tipo "Web"; en iOS usamos siempre el de Info.plist.
        DispatchQueue.main.async {
            if self.configure() {
                call.resolve()
            } else {
                call.reject("Falta GIDClientID en Info.plist")
            }
        }
    }

    @objc func signOut(_ call: CAPPluginCall) {
        DispatchQueue.main.async {
            GIDSignIn.sharedInstance.signOut()
            call.resolve()
        }
    }

    @objc func signIn(_ call: CAPPluginCall) {
        DispatchQueue.main.async {
            guard self.configure() else {
                call.reject("Falta GIDClientID en Info.plist")
                return
            }
            guard let presenter = self.bridge?.viewController else {
                call.reject("No se encontró la vista para mostrar el inicio de sesión")
                return
            }

            GIDSignIn.sharedInstance.signIn(withPresenting: presenter) { result, error in
                if let error = error {
                    if let gidError = error as? GIDSignInError, gidError.code == .canceled {
                        // Mismo código que usa Android al cancelar, para que la web no muestre alerta
                        call.reject("The user canceled the sign-in flow.", "12501")
                    } else {
                        call.reject(error.localizedDescription, nil, error)
                    }
                    return
                }

                guard let user = result?.user, let idToken = user.idToken?.tokenString else {
                    call.reject("No se pudo obtener el token de Google")
                    return
                }

                let profile = user.profile
                let imageUrl = profile?.imageURL(withDimension: 200)?.absoluteString ?? ""

                call.resolve([
                    "id": user.userID ?? "",
                    "email": profile?.email ?? "",
                    "name": profile?.name ?? "",
                    "givenName": profile?.givenName ?? "",
                    "familyName": profile?.familyName ?? "",
                    "imageUrl": imageUrl,
                    "idToken": idToken,
                    "authentication": [
                        "idToken": idToken,
                        "accessToken": user.accessToken.tokenString
                    ]
                ])
            }
        }
    }

    /// Por si iOS entrega el retorno de Google como URL (flujo de respaldo).
    @objc override public func load() {
        NotificationCenter.default.addObserver(
            self,
            selector: #selector(handleOpenUrl(_:)),
            name: Notification.Name.capacitorOpenURL,
            object: nil)
    }

    @objc func handleOpenUrl(_ notification: Notification) {
        guard let object = notification.object as? [String: Any],
              let url = object["url"] as? URL else { return }
        DispatchQueue.main.async {
            _ = GIDSignIn.sharedInstance.handle(url)
        }
    }
}
