# Setup
## Setup database

There is two tables in current database:
  `fullmeta` - Extracted mp3 tags from audio files
  `users` - Usernames and their hashed passwords

Recommended minimum of columns
`fullmeta` : 
```
  `id` - integer
  `filename`
  `title`
  `duration`
  `album`
  `genre`
  `artist`
  `year`
  `filesize` - audio file size in kibibytes
```
  `users` : 
```
  `id`       - integer (at the moment unused)
  `username` - username string
  `token`    - hashed, salted password
  `gender`   - (idk why it is here)
```


SQL create `fullmeta`
[/setup/createFullmeta.sql](/setup/createFullmeta.sql)

SQL create `users`
[/setup/createUsers.sql](/setup/createUsers.sql)

SQL create `apicooldown`
[/setup/createUsers.sql](/setup/createApiCooldown.sql)