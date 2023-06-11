from tinytag import TinyTag
import mysql.connector
import os, time

musicDir = "/home/intostor/Music/"

with open("failed.txt","w") as f:
  f.write("")

db = mysql.connector.connect(
  host="192.168.0.186",
  user="Admin",
  password="ffq6KLHYY583MdEahTYe",
  database="friedmusic",
  port=9889
)
cur = db.cursor()

files = [x for x in os.listdir(musicDir)]
files = sorted(files,key = lambda x: os.stat(musicDir+str(x)).st_atime,reverse=True)

t=time.time()



def getTracksFromDatabase():
  sql = "select filename from fullmeta"
  cur.execute(sql)
  res = [i[0] for i in cur.fetchall()]
  return res

def pushTracksToDatabase(tracks):
  print("Trying to push data to database")
  sql  = "REPLACE INTO `friedmusic`.`fullmeta`(`filename`,`title`,`duration`,`album`,`genre`,`artist`,`year`,`filesize`) VALUES(%s,%s,%s,%s,%s,%s,%s,%s)"
  cur.executemany(sql,tracks)
  db.commit()
  print("pushed succesfully")


def getAssociativeTracksArray(tracksFilenames):
  out = []
  for i,filename in enumerate(tracksFilenames):
    filepath = musicDir+filename

    tag = TinyTag.get(filepath)

    filesize   = int(os.path.getsize(filepath))

    title      = str(tag.title)
    artist     = str(tag.artist)
    album      = str(tag.album)
    samplerate = int(tag.samplerate)
    duration   = int(tag.duration)
    genre      = str(tag.genre)

    if title in (None,"","None") or artist in (None,"","None"):
      print(filename," doesn't have required metadata")
      with open("failed.txt","a") as f:
        f.write(filename+"\n")
      continue

    try:
      year       = int(tag.year)
    except Exception:
      year = None
    title.replace("'","\'")
    artist.replace("'","\'")
    album.replace("'","\'")
    genre.replace("'","\'")
    out.append((filename,title,duration,album,genre,artist,year,filesize))
    print(i,"getting associative array")
  return out


filesInDatabase = getTracksFromDatabase()

if len(filesInDatabase)<len(files):
  filesInDatabase,files = files,filesInDatabase
tracksToDatabase = [x for x in filesInDatabase if x not in files]


tracksMetadata = getAssociativeTracksArray(tracksToDatabase)
pushTracksToDatabase(tracksMetadata)
  

db.close()

with open("failed.txt","r") as f:
  print("Fix metadata in this files and re run")
  print(f.read())

print("elapsed: ", time.time()-t)
