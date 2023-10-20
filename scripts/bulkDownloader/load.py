import concurrent.futures, threading, time
import subprocess

import atexit


MAXPROCESSES = 12
COOKIE = "3:1697553124.5.0.1697553124185:ufR1sA:1.1.2:1|1777946379.-1.0.1:334934056.3:1697553124|3:10277329.348059._oa1GsiRoaBbnnXt6Yug4qAhLc8"

t=time.time()

urlsFile = open("urls.txt")
urls = urlsFile.readlines()
urlsFile.close()

def runPerlDownloader(url,delay=2,bitrate=320,dir="~/Temp"):
  cmd = f"perl ya.pl --bitrate {bitrate} --skip-existing -u '{url}' --cookie '{COOKIE}' --dir {dir} --delay {delay}"
  subprocess.run(cmd,shell=True)


pool = concurrent.futures.ThreadPoolExecutor(max_workers=MAXPROCESSES)

for url in urls:
  pool.submit(runPerlDownloader,url)


def oe():
  print("elapsed: ", time.time()-t)

atexit.register(oe)
