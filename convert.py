#!/usr/bin/python
import sys
import os
import shutil
import subprocess
from subprocess import call
def shellquote(s):
    return "'" + s.replace("'", "'\\''") + "'"
p1="ffprobe -v error -of flat=s=_ -select_streams v:0 -show_entries stream=width,height /var/lib/plexmediaserver/cloudLocal/Original/"
p2='"'+str(sys.argv[1])+'"'
cmd=p1+p2
process = subprocess.Popen([cmd], shell=True, stdout=subprocess.PIPE)
out, err = process.communicate()
out=out.split("\n")
widthList=out[0]
heightList=out[1]
widthList=widthList.split("=")
width=int(widthList[1])
heightList=heightList.split("=")
height=int(heightList[1])

FN='"'+str(sys.argv[1])+'"'
rFN=str(sys.argv[1])

copyMovies  ="cp /var/lib/plexmediaserver/cloudLocal/Original/"+FN+" /var/lib/plexmediaserver/cloudLocal/Movies/"

myFolder= (rFN.split("/", 1)[0])
myFolder= "\""+myFolder+"\""
print myFolder
if width == 1280 and height == 720:
    print "exact 720"
    os.system("rm -rf /var/lib/plexmediaserver/cloudLocal/Movies/"+myFolder)
    os.system("cp -R /var/lib/plexmediaserver/cloudLocal/Original/"+'\''+rFN+'\''+" /var/lib/plexmediaserver/cloudLocal/Movies/")

elif width == 1920 and height == 1080:
    print "exact 1080"
    os.system("rm -rf /var/lib/plexmediaserver/cloudLocal/Movies/"+myFolder)
    os.system("cp -R /var/lib/plexmediaserver/cloudLocal/Original/"+'\''+rFN+'\''+" /var/lib/plexmediaserver/cloudLocal/Movies/")

elif width <= 1280:
    print "Trans 720"
    os.system("rm -rf /var/lib/plexmediaserver/cloudLocal/Movies/"+myFolder)
    os.system("mkdir /var/lib/plexmediaserver/cloudLocal/Movies/"+myFolder)
    os.system("ffmpeg -i /var/lib/plexmediaserver/cloudLocal/Original/"+FN+" -vf scale=1280:720,setdar=16:9 -c:v libx264 -preset medium -crf 19 -sn -c:a aac -strict experimental -b:a 256k /var/lib/plexmediaserver/cloudLocal/Movies/"+FN)

else:
    print "Trans 1080"
    os.system("rm -rf /var/lib/plexmediaserver/cloudLocal/Movies/"+myFolder)
    os.system("mkdir /var/lib/plexmediaserver/cloudLocal/Movies/"+myFolder)
    os.system("ffmpeg -i /var/lib/plexmediaserver/cloudLocal/Original/"+FN+" -vf scale=1920:1080,setdar=16:9 -c:v libx264 -preset medium -crf 18 -sn -c:a aac -strict experimental -b:a 256k /var/lib/plexmediaserver/cloudLocal/Movies/"+FN)
