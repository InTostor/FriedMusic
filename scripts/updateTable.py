from tinytag import TinyTag
import mysql.connector
import os

musicDir = "/home/intostor/Music"

db = mysql.connector.connect(
  host="192.168.0.186",
  user="Admin",
  password="ffq6KLHYY583MdEahTYe",
  database="friedmusic",
  port=9889
)
cur = db.cursor()

files = [x for x in os.listdir(musicDir)]
lf=len(files)

for i,filename in enumerate(files):
  tag = TinyTag.get(musicDir+"/"+filename)

  filesize   = int(os.path.getsize(musicDir+"/"+filename))

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
  
  checkSql = "select count(filename) from `fullmeta` where filename = %s"
  cur.execute(checkSql,[filename])
  res = str(cur.fetchall())[2:3]
  if res == '0':
    sql = "REPLACE INTO `friedmusic`.`fullmeta`(`filename`,`title`,`duration`,`album`,`genre`,`artist`,`year`,`filesize`) VALUES(%s,%s,%s,%s,%s,%s,%s,%s)"
    vals = (filename,title,duration,album,genre,artist,year,filesize)
    cur.execute(sql,vals)
    db.commit()
  print(res, i+1,"/",lf)

db.close()

