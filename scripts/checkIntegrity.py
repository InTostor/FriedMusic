import os, mysql.connector

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

cur = db.cursor()

musicDir = "./../web/Music/"

files = os.listdir(musicDir)

def getTracksListFromDb():
  sql = f"select filename from {fullmetaTableName}"
  cur.execute(sql)
  res = [i[0] for i in cur.fetchall()]
  return res


filesDB = getTracksListFromDb()

ret = []

for f in filesDB:
  if not f in files:
    ret.append(f)

print(f)