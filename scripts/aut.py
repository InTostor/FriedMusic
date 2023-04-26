with open("genres.txt") as f:
  gs = [g.strip() for g in f]
gs.sort()

d = {}
for x in gs:
    d[x] = 1
gs = list(d.keys())
for h in gs:print(h)