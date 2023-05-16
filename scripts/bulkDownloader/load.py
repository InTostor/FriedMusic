import concurrent.futures, threading, time
import subprocess

import atexit


MAXPROCESSES = 20
COOKIE = "3:1681523873.5.0.1681523873804:CuF1sA:7a.1.2:1|1777946379.-1.0.1:334934056.3:1681523873|3:10268429.223663.5mirwNPdr-1CTpVkdTe_yG4v7RQ"

t=time.time()

urlsFile = open("urls.txt")
urls = urlsFile.readlines()
urlsFile.close()

def runPerlDownloader(url,delay=1,bitrate=320,dir="~/Music"):
  cmd = f"perl ya.pl --bitrate {bitrate} --skip-existing -u '{url}' --cookie '{COOKIE}' --dir {dir} --delay {delay}"
  subprocess.run(cmd,shell=True)


pool = concurrent.futures.ThreadPoolExecutor(max_workers=MAXPROCESSES)

for url in urls:
  pool.submit(runPerlDownloader,url)


def oe():
  print("elapsed: ", time.time()-t)

atexit.register(oe)
