import os
import sys
import json
import requests
from concurrent.futures import ThreadPoolExecutor

sysArgs = sys.argv

location_url_format = 'https://rapi.craigslist.org/web/v8/locations?cc=US&lang=en&lat={}&lon={}'

# Read input coordinates 2 at a time, with odd arguments being latitude and even arguments being longitude:
list_of_urls = []
for i in range(1, len(sysArgs), 2):
    lat = sysArgs[i]
    lon = sysArgs[i + 1]
    url = location_url_format.format(lat, lon)
    list_of_urls.append(url)

def get_url(url):
    return requests.get(url)
    
with ThreadPoolExecutor(max_workers=50) as pool:
    response_list = list(pool.map(get_url,list_of_urls))

stringified_output = '|\n'.join(map(lambda x: x.text, response_list))
print(stringified_output)

