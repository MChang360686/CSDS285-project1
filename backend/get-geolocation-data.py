import os
import sys
import json

import reverse_geocoder as rg

sysArgs = sys.argv

# Read input coordinates 2 at a time, with odd arguments being latitude and even arguments being longitude:
coordinates = []
for i in range(1, len(sysArgs), 2):
    lat = sysArgs[i]
    lon = sysArgs[i + 1]
    coordinates.append((lat, lon))

with open(os.devnull, "w") as devnull:
    old_stdout = sys.stdout
    sys.stdout = devnull
    results = rg.search(coordinates, mode=1)  # default mode = 2

stringified_output = '|\n'.join(map(lambda x: x.text, response_list))
print(stringified_output)
