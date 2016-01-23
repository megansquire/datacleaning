# -*- coding: utf-8 -*-
import sys
import random
import pymysql

password = sys.argv[1]
tableTargetSize = 1000;

# Open local database connection
db = pymysql.connect(host='localhost',
                     db='stackoverflow',
                     user='',
                     passwd=password,
                     port=3306,
                     charset='utf8mb4',
                     autocommit=True)
cursor = db.cursor()
cursor1 = db.cursor()

# this is a hack to get around Python not allowing me to execute dynamic tables
# reference: http://stackoverflow.com/questions/26497846/python-mysql-parameter-queries-for-dynamic-table-names

tableList = ['SELECT count(Id), min(Id), max(Id) FROM badges',
    'SELECT count(Id), min(Id), max(Id) FROM comments',
    'SELECT count(Id), min(Id), max(Id) FROM posts',
    'SELECT count(Id), min(Id), max(Id) FROM post_history',
    'SELECT count(Id), min(Id), max(Id) FROM post_links',
    'SELECT count(Id), min(Id), max(Id) FROM tags',
    'SELECT count(Id), min(Id), max(Id) FROM users',
    'SELECT count(Id), min(Id), max(Id) FROM votes']
    
testQueryList = ['INSERT IGNORE INTO test_badges SELECT * FROM badges WHERE Id = %s',
    'INSERT IGNORE INTO test_comments SELECT * FROM comments WHERE Id = %s',
    'INSERT IGNORE INTO test_posts SELECT * FROM posts WHERE Id = %s',
    'INSERT IGNORE INTO test_post_history SELECT * FROM post_history WHERE Id = %s',
    'INSERT IGNORE INTO test_post_links SELECT * FROM post_links WHERE Id = %s',
    'INSERT IGNORE INTO test_tags SELECT * FROM tags WHERE Id = %s',
    'INSERT IGNORE INTO test_users SELECT * FROM users WHERE Id = %s',
    'INSERT IGNORE INTO test_votes SELECT * FROM votes WHERE Id = %s']
    
testCountList = ['SELECT count(*) FROM test_badges',
    'SELECT count(*) FROM test_comments',
    'SELECT count(*) FROM test_posts',
    'SELECT count(*) FROM test_post_history',
    'SELECT count(*) FROM test_post_links',
    'SELECT count(*) FROM test_tags',
    'SELECT count(*) FROM test_users',
    'SELECT count(*) FROM test_votes']
i=0
for tableQuery in tableList:
    print("\n=== Now working on", tableQuery, "(test table ",i,")")
    
    try:
        cursor.execute(tableQuery)
        result = cursor.fetchone()
    except:
        print(cursor._last_executed)
        raise
    tableCount = result[0]
    tableMin = result[1]
    tableMax = result[2]
    
    # set up loop to grab a random row and insert into new table
    j=0;
    while j < tableTargetSize:

        r = random.randrange(tableMin,tableMax)
        print("Iteration",j,":",r);
        
        cursor1.execute(testQueryList[i],(r,));
        cursor1.execute(testCountList[i]);
        currentCount = cursor1.fetchone()[0]
        j = currentCount
        
    i = i+1
db.close()
