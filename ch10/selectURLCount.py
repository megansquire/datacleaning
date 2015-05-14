# selectURLCount.py
import csv
import MySQLdb

# Open database connection
db = MySQLdb.connect(host="localhost",
    user="username", 
    passwd="password", 
    db="ferguson", 
    use_unicode=True, 
    charset="utf8")
cursor = db.cursor()

cursor.execute('SELECT left(tdisplay, LOCATE(\'/\', 
    tdisplay)-1) as url, COUNT(tid) as num 
    FROM ferguson_tweets_urls 
    GROUP BY 1 ORDER BY 2 DESC LIMIT 15')

with open('fergusonURLcounts.tsv', 'wb') as fout:
    writer = csv.writer(fout)
    writer.writerow([ i[0] for i in cursor.description ])
    writer.writerows(cursor.fetchall())
