def parseTime(time):

    finalTime = ""
    #time format: 00:00:00 -> hh:mm:ss
    segmentList = list(time)
    if len(segmentList) == 3:
        finalTime += "0"
        finalTime += segmentList[0]
        finalTime += ":"
        finalTime += segmentList[1] + segmentList[2]
        finalTime += ":00"
    elif len(segmentList) == 4:
        finalTime += segmentList[0] + segmentList[1]
        finalTime += ":"
        finalTime += segmentList[2] + segmentList[3]
        finalTime += ":00"
    else:
        finalTime = "00:00:00"

    return finalTime

def parseClassDays(days):

    days = days.strip('"')
    dayList = list(days)
    #print dayList
    output = ""
    if len(dayList) == 0:
        output = "NA"
    else:
        for i in range(0,len(dayList)):
            output += dayList[i]
            #print dayList
            #print "i = " + str(i) + "    dayList-1 = " + str(len(dayList)-1)
            if i != (len(dayList)-1):
                output += ","
    #print output
    return output

def parseData():

    filein = open("data.csv","r")
    fileout1 = open("runningCourses.sql", "w")

    #running courses
    outputRunning = "INSERT INTO `cu_running_courses` ( `course_name`, `course_section`, `class_type`, `course_semester`, `course_year`, `seats_left`, `class_days`, `class_start`, `class_end`, `class_weeks_run`) VALUES"

    
    lineNumber = 0;
    for line in filein:

        if lineNumber != 0:
            lineSections = line.split(";")
            courseName = (lineSections[0].strip('"') + lineSections[1].strip('"'))
            if lineNumber > 1:
                outputRunning += ","
            outputRunning += "\n\t("
            outputRunning += "'" + courseName + "',"
            outputRunning += "'" + lineSections[2].strip('"') + "',"
            classType = lineSections[4].strip('"')
            outputRunning += "'" + classType + "',"
            outputRunning += "'fall',"
            outputRunning += "'2014',"
            seatsLeft = lineSections[8].strip("\r\n")
            if len(list(seatsLeft)) == 0:
                outputRunning += "'-1',"
            else:
                
                outputRunning += "'" + seatsLeft + "', "

            outputRunning += "'" + parseClassDays(lineSections[5]) + "',"
            outputRunning += "'" + parseTime(lineSections[6]) + "',"
            outputRunning += "'" + parseTime(lineSections[7]) + "',"
            outputRunning += "'weekly')"
            
            
        lineNumber += 1
        

    outputRunning += ";"
    fileout1.write(outputRunning)


parseData()
