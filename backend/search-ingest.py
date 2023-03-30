import sys
import requests
from concurrent.futures import ThreadPoolExecutor

search_parameter_url_encoded = sys.argv[1]

def format_url(url):
    return url.rstrip().format(search_parameter_url_encoded)

with open('generated-data/search_urls.txt') as file:
    list_of_urls = [format_url(line) for line in file]

def get_url(url):
    return requests.get(url)

with ThreadPoolExecutor(max_workers=50) as pool:
    response_list = list(pool.map(get_url,list_of_urls))

stringified_output = '|\n'.join(map(lambda x: x.text, response_list))
print(stringified_output)
