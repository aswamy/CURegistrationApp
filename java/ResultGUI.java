/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package sysc4504;

import java.awt.Color;
import java.awt.GridBagConstraints;
import java.awt.GridBagLayout;
import java.util.ArrayList;
import javax.swing.JLabel;
import javax.swing.JPanel;

/**
 *
 * @author BenjaminW
 */
public class ResultGUI extends JPanel {

    
    public ResultGUI(ArrayList<String> selectedCourses) {
            this.setBackground(Color.lightGray);
            setLayout(new GridBagLayout());
            GridBagConstraints c = new GridBagConstraints();
            JLabel paragraph = new JLabel("<html><h2> You have selected the following"
                    + " courses as completed:</h2><br/>"
            );
            for (String s : selectedCourses) {
                paragraph.setText(paragraph.getText() + s + "<br/>");
            } 
            paragraph.setText(paragraph.getText()+ "</html>");
            
            add(paragraph, c);
    }
    
}
