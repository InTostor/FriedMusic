from tinytag import TinyTag
import mysql.connector
import os, time

musicDir = "/home/intostor/Music/"

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
  sql  = "REPLACE INTO `friedmusic`.`fullmeta`(`filename`,`title`,`duration`,`album`,`genre`,`artist`,`year`,`filesize`) VALUES(%s,%s,%s,%s,%s,%s,%s,%s)"
  cur.executemany(sql,tracks)
  db.commit()


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
    try:
      year       = int(tag.year)
    except Exception:
      year = None
    title.replace("'","\'")
    artist.replace("'","\'")
    album.replace("'","\'")
    genre.replace("'","\'")
    out.append((filename,title,duration,album,genre,artist,year,filesize))
  return out


filesInDatabase = getTracksFromDatabase()

if len(filesInDatabase)<len(files):
  filesInDatabase,files = files,filesInDatabase
tracksToDatabase = [x for x in filesInDatabase if x not in files]


tracksMetadata = getAssociativeTracksArray(tracksToDatabase)
pushTracksToDatabase(tracksMetadata)



  
  

db.close()

print("elapsed: ", time.time()-t)