/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package sysc4505;
import java.awt.*;
import java.awt.event.*;
import javax.swing.*;
import java.net.*;
import java.io.*;
import java.util.Arrays;

/**
 *
 * @author BenjaminW
 */
public class ProgramTreeGUI extends JPanel {
    
       //Given a CSV string, construct a table by
       //parsing the string for the list of courses
       public ProgramTreeGUI(String programData) {

        setLayout(new GridBagLayout());
        GridBagConstraints c = new GridBagConstraints();
        c.fill = GridBagConstraints.HORIZONTAL;
        c.ipadx = 50;
        
        String[] csvRows = programData.split(";");
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
        String prevSemester = "fall";
        int col =0; //int to hold the column count
        int row =0;
        for (String csvRow : _csvRows) {

            String[] course = csvRow.split(",");
            int courseYear = Integer.parseInt(course[course_year]);
            String courseSemester = course[course_semester];

            //Changing semesters means moving to the next column
            if(!courseSemester.equals(prevSemester)){
//                System.out.println("Semester has changed");
                col++;
                row=0;
                prevSemester = courseSemester;
            }

//            System.out.println("---");
//            System.out.println(csvRow);
//            System.out.print("Column:");
//            System.out.println(col);
//            System.out.print("Row:");
//            System.out.println(row);

            c.gridx=col;
            c.gridy=row;
            add(new JLabel(course[course_name]), c);
            row++;
                    
                }
  
       };
       
}

