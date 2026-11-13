// swift-tools-version: 5.9
import PackageDescription

// El nombre "ViantrypGoogleSignin" debe coincidir con el que genera Capacitor CLI
// a partir del nombre npm "viantryp-google-signin".
let package = Package(
    name: "ViantrypGoogleSignin",
    platforms: [.iOS(.v15)],
    products: [
        .library(
            name: "ViantrypGoogleSignin",
            targets: ["ViantrypGoogleSigninPlugin"])
    ],
    dependencies: [
        .package(url: "https://github.com/ionic-team/capacitor-swift-pm.git", from: "8.0.0"),
        .package(url: "https://github.com/google/GoogleSignIn-iOS.git", from: "8.0.0")
    ],
    targets: [
        .target(
            name: "ViantrypGoogleSigninPlugin",
            dependencies: [
                .product(name: "Capacitor", package: "capacitor-swift-pm"),
                .product(name: "Cordova", package: "capacitor-swift-pm"),
                .product(name: "GoogleSignIn", package: "GoogleSignIn-iOS")
            ],
            path: "ios/Sources/ViantrypGoogleSigninPlugin")
    ]
)
