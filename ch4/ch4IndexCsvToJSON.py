import csv
import json

# read in the nodes file
with open('nodesCutName.gdf') as nodefile:
    nodefile_csv = csv.DictReader(nodefile)
    noutput = '['
    ncounter = 0;

    # process each dictionary row 
    for nrow in nodefile_csv:
        # look for ' in people's names
        nrow["name"] = str(nrow["name"]).replace("b'","").replace("'","")
        # put a comma between the entities
        if ncounter > 0:
            noutput += ','
        noutput += json.dumps(nrow)
        ncounter += 1
    noutput += /]'
    # write out a new file to disk
    f = open('complete.json','w')
    f.write('{')
    f.write('\"nodes\":' )
    f.write(noutput)
    
# read in the edge file
with open('edgelistIndex.csv') as edgefile:
    edgefile_csv = csv.DictReader(edgefile)
    eoutput = '['
    ecounter = 0;
    # process each dictionary row 
    for erow in edgefile_csv:
        # make sure numeric data is coded as number not string
        for ekey in erow:
            try:
                erow[ekey] = int(erow[ekey])
            except ValueError:
                # not an int
                pass
        # put a comma between the entities
        if ecounter>0:
            eoutput += ','
        eoutput += json.dumps(erow)
        ecounter += 1
    eoutput += ']'
    
    # write out a new file to disk
    f.write(',')
    f.write('\"edges\":')
    f.write(eoutput)
    f.write('}')
    f.close()
