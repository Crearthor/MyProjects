package Obrazy;

import java.awt.*;
import java.awt.image.BufferedImage;
import java.io.File;
import java.io.IOException;

import javax.swing.JButton;
import javax.imageio.ImageIO;
import javax.swing.JPanel;
import javax.swing.JTextField;
import javax.swing.event.ChangeEvent;
import javax.swing.event.ChangeListener;
import javax.swing.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

public class ObrazPanel extends JPanel implements ActionListener{

	private static final long serialVersionUID = 1L;
	private BufferedImage image;
	private JButton Red;
	private JButton Green;
	private JButton Blue;
	private JButton GrayScale;
	private JButton Sepia;
	private JTextField TBarwa;
	private JTextField TJasnosc;
	
	private JSlider Barwa;
	private JSlider Jasnosc;
	JPanel edycja = new JPanel();
	//private String press="";
	
	
	//Tworzenie i dodawanie przyciskow
	public ObrazPanel() throws IOException {

		Green = new JButton("Green");
		Blue = new JButton("Blue");
		Red = new JButton("Red");
		GrayScale = new JButton("GrayScale");
		Sepia = new JButton("sepia");
		
		Barwa = new JSlider(0,360,0);
		TBarwa = new JTextField("000");
		TBarwa.setEditable(false);
		
        
		Jasnosc = new JSlider(-60,100,0);
		TJasnosc = new JTextField("000");
		TJasnosc.setEditable(false);

        
		Green.addActionListener(this);
		Blue.addActionListener(this);
		Red.addActionListener(this);
		GrayScale.addActionListener(this);
		Sepia.addActionListener(this);
		
		
		Jasnosc.addChangeListener(new ChangeListener(){
			public void stateChanged(ChangeEvent e) {
				Przetwarzanie obraz = null;
				String image1 = "java.jpg";
			    JSlider source = (JSlider)e.getSource();
			    int v = (int)source.getValue();
			    TJasnosc.setText(Integer.toString(v));
			   	try {
					obraz = new Przetwarzanie(image1);
			   	}catch (IOException e1) {
					// TODO Auto-generated catch block
					e1.printStackTrace();
				}
			   	try {
					obraz.brightness(v);
				} catch (IllegalAccessException e2) {
					// TODO Auto-generated catch block
					e2.printStackTrace();
				}
			    File imageFile = new File("java1.jpg");	//tworze nowy plik
					try {
						obraz.save("JPG", "java1.jpg");	//zapisuje przetworzony obraz w nowym pliku
						image = ImageIO.read(imageFile);	//odczytuje obraz
						
					} catch (IOException e1) {
						System.err.println("Blad odczytu obrazka");  //jezeli sie nie uda odczytac obrazka
						e1.printStackTrace();
					}
					repaint();
			}   
		});
		
		Barwa.addChangeListener(new ChangeListener(){
			public void stateChanged(ChangeEvent e) {
				Przetwarzanie obraz = null;
				String image1 = "java.jpg";
			    JSlider source = (JSlider)e.getSource();
			    int h = (int)source.getValue();
			    //if (!source.getValueIsAdjusting()) {
			    TBarwa.setText(Integer.toString(h));
			   	try {
					obraz = new Przetwarzanie(image1);
			   	}catch (IOException e1) {
					// TODO Auto-generated catch block
					e1.printStackTrace();
				}
			    obraz.exaggeration(h,0);
			    
			    File imageFile = new File("java1.jpg");	//tworze nowy plik
				try {
					obraz.save("JPG", "java1.jpg");	//zapisuje przetworzony obraz w nowym pliku
					image = ImageIO.read(imageFile);	//odczytuje obraz						
				} catch (IOException e1) {
					System.err.println("Blad odczytu obrazka");  //jezeli sie nie uda odczytac obrazka
					e1.printStackTrace();
				}
			  //  }
				repaint();
			}   
		});
	
		
		add(Green);
		add(Blue);
		add(Red);
		add(GrayScale);
		add(Sepia);
		add(Barwa);
		add(Jasnosc);
		add(TJasnosc);
		add(Barwa);
		add(TBarwa);
		//otworzenie obrazka pierwotnego i utworzenie dostosowanego do jego rozmiarow okna
		
		File imageFile = new File("java.jpg");	//tworze nowy plik
		image = ImageIO.read(imageFile);
		Dimension dimension = new Dimension(image.getWidth(), image.getHeight());//Gdy obrazek jest za duzy wystepuje kiepskie skalowanie
		setPreferredSize(dimension);	//maksymalizacja okna psuje obrazek
		
		
	}

	//Rysowanie obrazka
	@Override
	protected void paintComponent(Graphics g) {
		super.paintComponent(g);
		g.drawImage(image, 0, 0, getWidth(), getHeight(), this);
	}

	//wykonywanie przyciskow
	public void actionPerformed(ActionEvent e)  {
		Object source = e.getSource(); 	//pobiera nazwe przycisku ktory zostal wcisniety
		String image1 = "java.jpg";  	//nazwa obrazka pierwotnego
		Przetwarzanie obraz;
		try {
			obraz = new Przetwarzanie(image1);
			if(source == Green)
				obraz.changeColor(2);
			else if(source == Red)
				obraz.changeColor(1);
			else if(source == Blue)
				obraz.changeColor(3);
			if(source == GrayScale)
				obraz.greyScale();
			if(source == Sepia)
				obraz.sepia();
		
			
			File imageFile = new File("java1.jpg");	//tworze nowy plik
			try {
				obraz.save("JPG", "java1.jpg");	//zapisuje przetworzony obraz w nowym pliku
				image = ImageIO.read(imageFile);	//odczytuje obraz
				
			} catch (IOException e1) {
				System.err.println("Blad odczytu obrazka");  //jezeli sie nie uda odczytac obrazka
				e1.printStackTrace();
			}
		
			repaint();  //ponowne malowanie (zapobieglo opcji ciaglego minmalizowania i maksymalizowania okienka aby dokonac zmian)
		} catch (IOException e1) {
			
			e1.printStackTrace();
		}
	}
}