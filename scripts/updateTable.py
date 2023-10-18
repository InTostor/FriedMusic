from tinytag import TinyTag
import mysql.connector
import os, time

musicDir = "/home/intostor/Music/"
sortMode = "name" # name | atime

fullmetaTableName = 'fullmeta'
try:
  db = mysql.connector.connect(
    host="192.168.0.186",
    user="Admin",
    password="ffq6KLHYY583MdEahTYe",
    database="friedmusic",
    port=9889
  )
except Exception as e:
  print("Database connection error")
  print(e)
  exit()

failedTracks = []
with open("failed.txt","w") as f:
  f.write("")


cur = db.cursor()

# files = [x for x in os.listdir(musicDir)]
files = os.listdir(musicDir)

match sortMode:
  case "name":
    files = sorted(files)
  case "atime":
    files = sorted(files,key = lambda x: os.stat(musicDir+str(x)).st_atime,reverse=True)

t=time.time()

def printTimestamp():
  """inline"""
  print(str(round(time.time()-t,4)).ljust(8),end=" | ")

def getTracksFromDatabase():
  sql = f"select filename from {fullmetaTableName}"
  cur.execute(sql)
  res = [i[0] for i in cur.fetchall()]
  printTimestamp()
  print("got tracks from databse")
  return res

def pushTracksToDatabase(tracks):
  printTimestamp()
  print("Trying to push data to database")
  sql  = f"INSERT IGNORE INTO {fullmetaTableName} (`filename`,`title`,`duration`,`album`,`tracknumber`,`genre`,`artist`,`year`,`filesize`) VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s)"
  cur.executemany(sql,tracks)
  db.commit()
  printTimestamp()
  print("pushed succesfully")

def truncateTable():
  sql = f"TRUNCATE TABLE {fullmetaTableName}"
  cur.execute(sql)
  db.commit()

def getAssociativeTracksArray(tracksFilenames):
  printTimestamp()
  print("getting tags from files")
  out = []

  for i,filename in enumerate(tracksFilenames):
    if i%10==0:
      printTimestamp()
      print("files checked",i)

    filepath = musicDir+filename
    if not TinyTag.is_supported(filepath):
      print(filepath,"not supported")
      continue

    tag = TinyTag.get(filepath)
    

    filesize   = int(os.path.getsize(filepath))

    title      = str(tag.title)
    if title in (None,"","None"):
      failedTracks.append(filename)
      print(filename,"doesn't have title")
      continue

    artist     = str(tag.artist)
    if artist in (None,"","None"):
      failedTracks.append(filename)
      print(filename,"doesn't have title")
      continue

    duration   = int(tag.duration)
    if duration <= 30:
      failedTracks.append(filename)
      print(filename,"is 30 sec. long. It's seems like file is constrained")
      continue


    album      = str(tag.album)
    # samplerate = int(tag.samplerate) # not used because most of tracks have same quality    
    genre      = str(tag.genre)

    # sometimes track doesnt have number
    tracknumber = None
    if hasattr(tag,"track"):
      if str(tag.track).isdigit():
        tracknumber= int(tag.track)

    # or year
    year = None
    if hasattr(tag,"year"):
      if str(tag.year).isdigit() :
        year= int(tag.year)

    title.replace("'","\'")
    artist.replace("'","\'")
    album.replace("'","\'")
    genre.replace("'","\'")
    out.append((filename,title,duration,album,tracknumber,genre,artist,year,filesize))
  with open("failed.txt","a") as f:
    f.write("\n".join(failedTracks))
  return out


filesInDatabase = getTracksFromDatabase()

if len(filesInDatabase)<len(files):
  filesInDatabase,files = files,filesInDatabase

printTimestamp()
print("removing copies")
tracksToDatabase = [x for x in filesInDatabase if x not in files]
printTimestamp()
print("removed copies")


tracksMetadata = getAssociativeTracksArray(tracksToDatabase)
pushTracksToDatabase(tracksMetadata)


print("elapsed: ", time.time()-t)
# truncateTable()
db.close()

with open("failed.txt","r") as f:
  fstring = f.read()
  if len(fstring)!=0:
    print("Fix metadata in this files and re run")
    print(fstring)


