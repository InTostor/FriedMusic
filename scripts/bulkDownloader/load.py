import concurrent.futures, threading, time
import subprocess

import atexit


MAXPROCESSES = 12
COOKIE = "3:1696148539.5.0.1696148539996:BfR1sA:8f.1.2:1|1777946379.-1.0.1:334934056.3:1696148539|3:10276549.570400.zKfy0CuOPwfG-0iOPQO5hTx58rE"

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
