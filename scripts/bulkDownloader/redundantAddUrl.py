with open("urls.txt") as f:
  urls = [url.strip() for url in f]

print("type url and hit enter")
while True:
  line = input(":")
  if line not in urls:
    with open("urls.txt","a") as f:
      f.write(line+"\n")
  else:
    print("exists")