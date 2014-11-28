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

        this.setBackground(Color.lightGray);
        setLayout(new GridBagLayout());
        GridBagConstraints labelc = new GridBagConstraints();
        GridBagConstraints itemc  = new GridBagConstraints();
        GridBagConstraints fillerc  = new GridBagConstraints();
        //This is used to add blank space to fill the screen
        fillerc.fill = GridBagConstraints.HORIZONTAL;
        fillerc.anchor = GridBagConstraints.NORTHWEST;
        fillerc.weightx = 1.0;
        fillerc.gridwidth = GridBagConstraints.REMAINDER;
        
        //This is used by items to give them a width of 2
        itemc.fill = GridBagConstraints.HORIZONTAL;
        itemc.anchor = GridBagConstraints.NORTHWEST;
        itemc.weightx = 1.0;
        itemc.gridwidth = 2;
        itemc.ipadx = 50;
        itemc.ipady = 10;
        
        /*This is used by labels and takes up the smallest amount of space
        *possible
        */
        labelc.fill = GridBagConstraints.HORIZONTAL;
        labelc.anchor = GridBagConstraints.NORTHWEST;
        labelc.weightx = 0.0;
        labelc.gridwidth = 1;
        labelc.ipadx = 50;
        labelc.ipady = 10;
        
        //Create a banner
        JLabel banner = new JLabel("Carleton University Registration App",SwingConstants.CENTER);
        banner.setOpaque(true);
        banner.setForeground(Color.white);
        banner.setBackground(Color.black);
        banner.setFont(new Font(banner.getName(), Font.ITALIC, 32));
        add(banner,fillerc);
        //add(new JLabel(), fillerc);
        
        //Create the input forms
        add(new JLabel("Student #:"), labelc);
        studentNum = new JTextField("10080000");
        add(studentNum, itemc);
        add(new JLabel(), fillerc);
        
        add(new JLabel("Degree:"), labelc);
        selectDegree = new JComboBox(degrees);
        add(selectDegree, itemc);
        add(new JLabel(), fillerc);
        
        add(new JLabel("OnTrack:"), labelc);
        onTrack = new JCheckBox();
        onTrack.setSelected(true); //Assume on track by default
        add(onTrack, itemc);
        add(new JLabel(), fillerc);
        
        add(new JLabel("Year Completed:"), labelc);
        String[] years = {"0", "1", "2", "3"};
        selectYear = new JComboBox(years);
        add(selectYear, itemc);
        add(new JLabel(), fillerc);
        
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
        add(new JLabel(), labelc);     
        add(button, itemc);
        /* Push all of the other components to the top of the screen
        * by setting this last component to take up ALL of the vertical space
        * that is unused.
        */
        fillerc.weighty =1; 
        add(new JLabel(), fillerc);
    };
    
    /*
    * This function sends the student#, degree, years completed, and on track 
    * status to the server. It build an array that contains these items
    * as wells as the response text
    * @return An array of strings the contains, student#,degree,years completed,
    on track status, and the server response text
    */
    private String[] sendUserInputs(){
        
        String serverAddress = "http://" +host + "/CURegistrationApp/view1b.php?viewType=JAVA";
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
