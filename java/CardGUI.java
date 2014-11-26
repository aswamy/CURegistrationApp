/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package sysc4504;

import java.awt.*;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.URL;
import javax.swing.JFrame;
import javax.swing.JPanel;

/**
 *
 * @author BenjaminW
 */
public class CardGUI extends JFrame {
    private static final String LOGIN_PANEL = "Login Panel";
    private static final String PROGRAM_PANEL = "ProgramTree Panel";
    private final JPanel content;
    String host = "localhost";
    
    public CardGUI() {
        CardLayout cardLayout = new CardLayout();
        setExtendedState(Frame.MAXIMIZED_BOTH);  
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setTitle("SYSC 4504 JAVA CLIENT");
        content = (JPanel) getContentPane();
        content.setLayout(cardLayout);
        //Pass in the cardlayoutGUI and the panel to traverse to on 
        // a successful login
        String[] degrees = fetchDegreeList().split(" "); //Each degree is seperated by white space
        JPanel loginPanel = new LoginGUI(degrees,content,cardLayout,PROGRAM_PANEL);
        content.add(loginPanel, LOGIN_PANEL);
        pack();
        setVisible(true);
    }
    
        private String fetchDegreeList(){
        
        String serverAddress = "http://" +host + "/view1a.php?viewType=JAVA";
        String responseText;
         // GET method
        
        try{
            URL url = new URL(serverAddress);

            BufferedReader in = new BufferedReader(
            new InputStreamReader(url.openStream()) );

            // responseText is the list of courses to populate the program tree with
            responseText = in.readLine(); 
            return responseText;
        }catch(Exception ex){
            responseText = "";
            return responseText;
        }

    }
    
    private static void createAndShowGUI() {
        //Create and set up the window.
        JFrame frame = new CardGUI();
    }
    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        //Schedule a job for the event-dispatching thread:
        //creating and showing this application's GUI.
        javax.swing.SwingUtilities.invokeLater(new Runnable() {
            public void run() {
                createAndShowGUI();
            }
        });
    }  
}
