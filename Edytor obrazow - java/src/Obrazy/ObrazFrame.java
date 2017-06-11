package Obrazy;

import java.io.IOException;

import javax.swing.JFrame;
import javax.swing.JPanel;

public class ObrazFrame extends JFrame {

	/**
	 * 
	 */
	private static final long serialVersionUID = 1L;

	public ObrazFrame() throws IOException {
		super("Program obrazkowy");  //nazwa okienka

		JPanel obrazPanel = new ObrazPanel();
		add(obrazPanel); //dodawanie panelu

		setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		pack();//dostosowanie okienka 
		setVisible(true);
	}
}