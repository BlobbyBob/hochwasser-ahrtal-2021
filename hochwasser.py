#!/usr/bin/env python

def readType(prompt, type=int) -> int:
    while True:
        v = input(prompt)
        try:
            v = type(v)
            return v
        except ValueError:
            print("Ungültige Eingabe!")


def parseLatLong(latlong):
    if latlong[:3] == 'geo':
        print("Geo URIs sind noch nicht implementiert")
        return False, False
    parts = latlong.split('/')
    if len(parts) < 2:
        print("Ungültiges Format")
        return False, False
    return float(parts[-2]), float(parts[-1])


towns = [("1", "Schuld"),
         ("2", "Insul"),
         ("3", "Fuchshofen"),
         ("4", "Antweiler"),
         ("5", "Müsch"),
         ("6", "Ahrdorf"),
         ("7", "Ahrhütte"),
         ("8", "Dümpelfeld"),
         ("9", "Hönningen"),
         ("10", "Ahrbrück"),
         ("12", "Altenahr"),
         ("13", "Mayschoß"),
         ("14", "Rech"),
         ("15", "Dernau"),
         ("17", "Bad Neuenahr-Ahrweiler"),
         ("19", "Sinzig"),
         ("20", "Liers")]

types = ["twitter", "youtube", "iframe", "link", "reddit"]
formats = ["image", "video"]

print("Hochwasser SQL Generator v1")
print("Städte:")
for town in towns:
    print(f"  {town[0]}) {town[1]}")

town = readType("Stadt: ", int)
title = input("Name: ")
day = readType("Tag: ", int)
hour = readType("Stunde: ", int)
latlong = input("Koordinaten: ")
print("Typen:")
for i, type in enumerate(types):
    print(f"  {i + 1}) {type}")
type = types[readType("Typ:", int) - 1]
print("Formate:")
for i, format in enumerate(formats):
    print(f"  {i + 1}) {format}")
format = formats[readType("Format:", int) - 1]

while True:
    lat, long = parseLatLong(latlong)
    if lat: break

data = ''

if type == 'twitter':
    id = input("Tweet ID: ")
    data = '{"id":"' + id + '"}'

elif type == 'youtube':
    videoId = input("Video ID: ")
    start = input("Start (optional): ")
    if len(start) == 0:
        data = '{"videoId":"' + videoId + '"}'
    else:
        data = '{"videoId":"' + videoId + '","start":' + start + '}'

elif type == 'iframe':
    url = input("URL: ")
    height = input("Höhe (default=1000): ")
    if len(height) == 0:
        height = "1000"
    data = '{"url":"' + url + '","height":' + height + '}'

elif type == 'link':
    url = input("URL: ")
    data = '{"url":"' + url + '"}'

elif type == 'reddit':
    sub = input("Sub: ")
    postId = input("Post ID: ")
    data = '{"sub":"' + sub + ',"postId":"' + postId + '"}'

with open("queries.txt", "a") as f:
    f.write("INSERT INTO `media` (`id`, `town`, `title`, `timestamp`, `latitude`, `longitude`, `type`, `format`, `data`) "
            f"VALUES (NULL, '{town}', '{title}', '2021-07-{day} {hour}:00:00', '{lat}', '{long}', '{type}', '{format}', '{data}');\n")

print("Query nach queries.txt geschrieben")
