import re
import io

row = []

infile  = io.open('django13-sept-2014.html', 'r', encoding='utf8')
outfile = io.open('django13-sept-2014.csv', 'a+', encoding='utf8')
for line in infile:
    pattern = re.compile(ur'<li class=\"le\" rel=\"(.+?)\"><a href=\"#(.+?)\" name=\"(.+?)<\/span> (.+?)</li>', re.UNICODE)
    if pattern.search(line):
        username = pattern.search(line).group(1)   
        linenum = pattern.search(line).group(2)
        message = pattern.search(line).group(4)
        row.append(linenum)
        row.append(username)
        row.append(message)
        outfile.write(', '.join(row))
        outfile.write(u'\n')
        row = []
infile.close()
