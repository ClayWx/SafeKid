

import java.awt.Dimension;
import java.awt.FlowLayout;
import java.awt.image.BufferedImage;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLConnection;
import java.util.Scanner;
import java.util.concurrent.Executor;
import java.util.concurrent.Executors;
import java.util.concurrent.ThreadFactory;

import javax.swing.JFrame;
import javax.swing.JTextArea;

import com.github.sarxos.webcam.Webcam;
import com.github.sarxos.webcam.WebcamPanel;
import com.github.sarxos.webcam.WebcamResolution;
import com.google.zxing.BinaryBitmap;
import com.google.zxing.LuminanceSource;
import com.google.zxing.MultiFormatReader;
import com.google.zxing.NotFoundException;
import com.google.zxing.Result;
import com.google.zxing.client.j2se.BufferedImageLuminanceSource;
import com.google.zxing.common.HybridBinarizer;


public class WebcamQRCodeExample extends JFrame implements Runnable, ThreadFactory {

	private static final long serialVersionUID = 6441489157408381878L;

	private Executor executor = Executors.newSingleThreadExecutor(this);

	private Webcam webcam = null;
	private WebcamPanel panel = null;
	private JTextArea textarea = null;

	public WebcamQRCodeExample() {
		super();

		setLayout(new FlowLayout());
		setTitle("Read QR / Bar Code With Webcam");
		setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);

		Dimension size = WebcamResolution.QVGA.getSize();

		webcam = Webcam.getWebcams().get(0);
		webcam.setViewSize(size);

		panel = new WebcamPanel(webcam);
		panel.setPreferredSize(size);

		textarea = new JTextArea();
		textarea.setEditable(false);
		textarea.setPreferredSize(size);

		add(panel);
		add(textarea);

		pack();
		setVisible(true);

		executor.execute(this);
	}

	@Override
	public void run(){
		String temp_result ="";
		int option;
		
		boolean flag = false;
		
		Scanner scan = new Scanner(System.in);
		
		System.out.println("Welcome to school");
		System.out.println("Selection otion:");
		System.out.println("Option 1: Scan");
		System.out.println("Option 2: Purchase");
		System.out.println("Option 3: System Exit");
		
		option = scan.nextInt();
		
		while (!flag){
			
			if (option == 1){
				
				do {
					try {
						Thread.sleep(100);
					} catch (InterruptedException e) {
						e.printStackTrace();
					}

					Result result = null;
					BufferedImage image = null;

					if (webcam.isOpen()) {

						if ((image = webcam.getImage()) == null) {
							continue;
						}

						LuminanceSource source = new BufferedImageLuminanceSource(image);
						BinaryBitmap bitmap = new BinaryBitmap(new HybridBinarizer(source));

						try {
							result = new MultiFormatReader().decode(bitmap);
						} catch (NotFoundException e) {
							// fall thru, it means there is no QR code in image
						}
					}

					if (result != null) {
						
						if(temp_result.equals(result.getText())==true){
							//System.out.println("Skipped");
							continue;
						}
						
						temp_result = result.getText();
						
						//set php integration here !!!
						URL url = null;
						try {
							url = new URL("http://localhost/angelhack/inout.php?submit=&sid=" + temp_result);
						} catch (MalformedURLException e) {
							// TODO Auto-generated catch block
							e.printStackTrace();
						}
				        URLConnection connection = null;
						try {
							connection = url.openConnection();
							connection.connect();
						} catch (IOException e) {
							// TODO Auto-generated catch block
							e.printStackTrace();
						}
				        
				        BufferedReader in = null;
						try {
							in = new BufferedReader(new InputStreamReader(connection.getInputStream()));
						} catch (IOException e1) {
							// TODO Auto-generated catch block
							e1.printStackTrace();
						}
				        String inputLine;
				 
				        try {
							while((inputLine = in.readLine()) != null)
							{
							    System.out.println(inputLine);
							}
						} catch (IOException e) {
							// TODO Auto-generated catch block
							e.printStackTrace();
						}
				 
				        try {
							in.close();
						} catch (IOException e) {
							// TODO Auto-generated catch block
							e.printStackTrace();
						}
						
						textarea.setText(result.getText());
					}

				} while (true);
			}
			
			else if (option == 2){
				
				do {
					try {
						Thread.sleep(100);
					} catch (InterruptedException e) {
						e.printStackTrace();
					}

					Result result = null;
					BufferedImage image = null;

					if (webcam.isOpen()) {

						if ((image = webcam.getImage()) == null) {
							continue;
						}

						LuminanceSource source = new BufferedImageLuminanceSource(image);
						BinaryBitmap bitmap = new BinaryBitmap(new HybridBinarizer(source));

						try {
							result = new MultiFormatReader().decode(bitmap);
						} catch (NotFoundException e) {
							// fall thru, it means there is no QR code in image
						}
					}

					if (result != null) {
						
						if(temp_result.equals(result.getText())==true){
							//System.out.println("Skipped");
							continue;
						}
						
						temp_result = result.getText();
						
						//set php integration here !!!
						URL url = null;
						try {
							url = new URL("http://localhost/angelhack/setSID.php?sid=" + temp_result);
						} catch (MalformedURLException e) {
							// TODO Auto-generated catch block
							e.printStackTrace();
						}
				        URLConnection connection = null;
						try {
							connection = url.openConnection();
							connection.connect();
						} catch (IOException e) {
							// TODO Auto-generated catch block
							e.printStackTrace();
						}
				        
				        BufferedReader in = null;
						try {
							in = new BufferedReader(new InputStreamReader(connection.getInputStream()));
						} catch (IOException e1) {
							// TODO Auto-generated catch block
							e1.printStackTrace();
						}
				        String inputLine;
				 
				        try {
							while((inputLine = in.readLine()) != null)
							{
							    System.out.println(inputLine);
							}
						} catch (IOException e) {
							// TODO Auto-generated catch block
							e.printStackTrace();
						}
				 
				        try {
							in.close();
						} catch (IOException e) {
							// TODO Auto-generated catch block
							e.printStackTrace();
						}
						
						textarea.setText(result.getText());		
						
						
					}

				} while (true);
			}
			
			else if (option == 3){
				System.out.println("SYSTEM EXITED. HAVE A NICE DAY.");
				break;
			}
		}
	}

	@Override
	public Thread newThread(Runnable r) {
		Thread t = new Thread(r, "example-runner");
		t.setDaemon(true);
		return t;
	}

	public static void main(String[] args) {
		new WebcamQRCodeExample();
	}
}