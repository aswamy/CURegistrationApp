/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package sysc4504;
import java.awt.*;
import javax.swing.*;
import java.util.Arrays;
import javax.swing.border.Border;

/**
 *
 * @author BenjaminW
 */
public class ProgramTreeGUI extends JPanel {
    
    
       //Given an array that contains: Degree, Student#,Years Done, On Track and
       // a CSV list of courses, create a program tree
       public ProgramTreeGUI(String[] programData) {

        Border borderF = BorderFactory.createLineBorder(Color.BLACK, 1);
        Border borderW = BorderFactory.createLineBorder(Color.GRAY, 1);
        setLayout(new GridBagLayout());
        GridBagConstraints c = new GridBagConstraints();
        c.fill = GridBagConstraints.HORIZONTAL;
        c.ipadx = 50;
        c.ipady = 20;
        

        String[] csvRows = programData[4].split(";");
        //The first row of the CSV is the column names
        //course_name,course_year,course_semester,course_size;
        String[] colNames = csvRows[0].split(",");
        //These are the positions of each string in the colNames list
        int course_name = Arrays.asList(colNames).indexOf("course_name");
        int course_year = Arrays.asList(colNames).indexOf("course_year");
        int course_semester = Arrays.asList(colNames).indexOf("course_semester");
        int course_size = Arrays.asList(colNames).indexOf("course_size");

        //Drop the first element of csvRows 
        //because it was just the colunm names
        String[] _csvRows = Arrays.copyOfRange(csvRows, 1, csvRows.length);

        /*
        We assume that the server has sorted the csv such that
        it is sorted by year, and semester.
        (e.g 1 comes before 2, and fall comes before winter)

        This assumption makes the clients job easier because it
        does not need to find indeces and do the sorting itself.

        For each row the client just needs to figure out what column
        and row it should belong to. We just check if the semester is
        toggling between rows, and if so that means we must
        move to the next column
        */
        String prevSemester = "winter";
        int col =-1; //int to hold the column count
        int row =2; //Start at row 2 to leave room for column titles
        for (String csvRow : _csvRows) {

            String[] course = csvRow.split(",");
            int courseYear = Integer.parseInt(course[course_year]);
            String courseSemester = course[course_semester];

            //Changing semesters means moving to the next column
            if(!courseSemester.equals(prevSemester)){
//                System.out.println("Semester has changed");
                col++;
                row=2;
                c.gridx=col;
                c.gridy=row-1;
                JLabel semesterTitle = new JLabel(course[course_semester] +","+course[course_year]);
                Border border = course[course_semester].equals("fall") ? borderF : borderW;
                semesterTitle.setBorder(border);
                semesterTitle.setFont(new Font("Serif", Font.BOLD, 16));
                add(semesterTitle, c);
                prevSemester = courseSemester;
            }

            c.gridx=col;
            c.gridy=row;
            JLabel courseName = new JLabel(course[course_name]);
            Border border = course[course_semester].equals("fall") ? borderF : borderW;
            courseName.setBorder(border);
            add(courseName, c);
            c.gridy=row+1;
            JCheckBox checkbox = new JCheckBox();
            checkbox.setBorder(border);
            checkbox.setBorderPainted(true);
            add(checkbox, c);
            row=row+2;
                    
        }
        //AFTER making the table we add a title.
        //We do this because we don't know how long the gridwidth should be beforehand.
        c.gridx=0;
        c.gridy=0;
        c.gridwidth=col+1;
        JLabel title = new JLabel(programData[1]+ " : " + programData[0] + " ONTRACK:"+programData[3], JLabel.CENTER);
        title.setFont(new Font("Serif", Font.BOLD, 32));
        add(title, c);
        c.gridwidth=0;
  
       };

       
}

