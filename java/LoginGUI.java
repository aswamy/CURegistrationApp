/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package sysc4504;
import java.awt.*;
import java.awt.event.*;
import javax.swing.*;
import java.net.*;
import java.io.*;

/**
 *
 * @author BenjaminW
 *
 */
public class LoginGUI extends JPanel{

    //Define my input fields
    JTextField studentNum;
    JCheckBox onTrack;
    JComboBox  selectDegree,selectYear; 
    String host = "localhost";
    
    /* Constuctor for the loginGUI.
    *  This constructs a frame which will be packed with
    *  text fields for student number, login, password
    *  @param degrees The list of degrees to populate the drop down list
    *  @param content The content pane
    *  @param cardLayout The cardlayout 
    *  @param nextPanelName The name of the next panel to travel to on successful login
    */
    public LoginGUI(String[] degrees, final JPanel content,final CardLayout cardLayout, final String nextPanelName) {

        setLayout(new GridBagLayout());
        GridBagConstraints c = new GridBagConstraints();
        c.fill = GridBagConstraints.HORIZONTAL;
        c.ipadx = 200;
        
        /* first row*/
        c.gridx=0;
        c.gridy=0;
        add(new JLabel("Student #:"), c);
        studentNum = new JTextField();
        c.gridx = 1;
        c.gridy = 0;
        add(studentNum, c);   
        
        /* second row*/
        c.gridx=0;
        c.gridy=1;
        add(new JLabel("Degree:"), c);
        //String[] degrees = {"CE", "CSE", "SE", "EE"}; //TODO get from server.
        selectDegree = new JComboBox(degrees);
        c.gridx = 1;
        c.gridy = 1;
        add(selectDegree, c);
        
        /*third row*/
        c.gridx=0;
        c.gridy=2;
        add(new JLabel("OnTrack:"), c);
        onTrack = new JCheckBox();
        onTrack.setSelected(true); //Assume on track by default
        c.gridx = 1;
        c.gridy = 2;
        add(onTrack, c);
        
        /*fourth row*/
        c.gridx=0;
        c.gridy=3;
        add(new JLabel("Year Completed:"), c);
        String[] years = {"0", "1", "2", "3"};
        selectYear = new JComboBox(years);
        c.gridx = 1;
        c.gridy = 3;
        add(selectYear, c);
        
         /*4th row*/
        c.gridx=0;
        c.gridy=5;
        c.gridwidth=2;
        JButton button =new JButton("Done");
        
        //On button press, get the necessary info needed to make
        //the program tree, and switch the cardGUI to the program tree
        button.addActionListener( new ActionListener() {
                @Override
        	public void actionPerformed(ActionEvent e) {
        		String[] userData = sendUserInputs();
        		JPanel programPanel = new ProgramTreeGUI(userData,content,cardLayout,nextPanelName);
        		content.add(programPanel, nextPanelName);
        		cardLayout.show(content, nextPanelName);	
        	}
        	
        	}
        );
             
        add(button, c);
    };
    
    /*
    * This function sends the student#, degree, years completed, and on track 
    * status to the server. It build an array that contains these items
    * as wells as the response text
    * @return An array of strings the contains, student#,degree,years completed,
    on track status, and the server response text
    */
    private String[] sendUserInputs(){
        
        String serverAddress = "http://" +host + "/view1b.php?viewType=JAVA";
        String[] userData = new String[5]; //Store Degree, On Track, Year
        String responseText;
        userData[0] = studentNum.getText();
        userData[1] = String.valueOf(selectDegree.getSelectedItem());
        userData[2] = String.valueOf(selectYear.getSelectedItem());
        userData[3] = String.valueOf(onTrack.isSelected());
        
        String parameters = 
            "&studentnum=" +userData[0]+
            "&degree="    +userData[1]+
            "&yearscompleted=" +userData[2]+
            "&ontrack="   +userData[3];
         // GET method
        
        try{
            URL url = new URL(serverAddress + parameters);

            BufferedReader in = new BufferedReader(
            new InputStreamReader(url.openStream()) );

            // responseText is the list of courses to populate the program tree with
            responseText = in.readLine();
            userData[4] = responseText;
            return userData;
        }catch(Exception ex){
            userData[4] = "";
            return userData;
        }

    }
       
}
