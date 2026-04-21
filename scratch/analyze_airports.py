
import csv

input_file = r'C:\laragon\www\viantryp\storage\app\imports\airports.csv'
output_list = []

# Common patterns/cities to look for in the name that might be mapped to a confusing city
major_city_hints = [
    'Atenas', 'Athens', 'Londres', 'London', 'París', 'Paris', 'Tokio', 'Tokyo',
    'Bruselas', 'Brussels', 'Milán', 'Milan', 'Estocolmo', 'Stockholm', 'Roma', 'Rome',
    'Venecia', 'Venice', 'Berlín', 'Berlin', 'Madrid', 'Barcelona', 'Lisboa', 'Lisbon',
    'Viena', 'Vienna', 'Praga', 'Prague', 'Ámsterdam', 'Amsterdam', 'Córcega', 'Corsica',
    'Seúl', 'Seoul', 'Osaka', 'Nagoya', 'Nueva York', 'New York', 'Washington', 'Chicago',
    'Los Ángeles', 'Los Angeles', 'San Francisco', 'Miami', 'Buenos Aires', 'Santiago',
    'Bogotá', 'Medellín', 'Cali', 'México', 'Mexico', 'Río de Janeiro', 'Rio de Janeiro',
    'Sao Paulo', 'Munich', 'Múnich', 'Frankfurt', 'Helsinki', 'Oslo', 'Copenhague', 'Copenhagen',
    'Varsovia', 'Warsaw', 'Moscú', 'Moscow', 'Estambul', 'Istanbul', 'Dubái', 'Dubai'
]

with open(input_file, mode='r', encoding='utf-8', errors='replace') as f:
    reader = csv.reader(f, delimiter=';')
    for row in reader:
        if len(row) < 5: continue
        
        a_type, name, country, city, iata = row
        
        # We only care about medium and large airports
        if a_type not in ['large_airport', 'medium_airport']: continue
        
        # Logic: If name contains a major city hint, but city field DOES NOT contain it
        match_found = False
        suggested_city = None
        
        for hint in major_city_hints:
            if hint.lower() in name.lower():
                # If the city field is already basically the hint, ignore it (not confusing)
                if hint.lower() in city.lower():
                    continue
                
                # Check for specific known redirects
                match_found = True
                suggested_city = hint
                break
        
        if match_found:
            output_list.append({
                'iata': iata,
                'name': name,
                'country': country,
                'current_city': city,
                'suggested': suggested_city,
                'type': a_type
            })

# Sort by large_airport first
output_list.sort(key=lambda x: (x['type'] != 'large_airport', x['suggested']))

# Print top 50
for item in output_list[:60]:
    print(f"{item['iata']};{item['name']};{item['current_city']};{item['suggested']};{item['country']}")
