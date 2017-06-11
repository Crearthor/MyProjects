#include <SFML/Graphics.hpp>
#include <iostream>
#include <stdio.h>
#include <cstdlib>
using namespace std;

struct Pole
{
    bool mina;
    int odkryte;
    int flaga;
    int x,y;
};

class Plansza
{
    sf::VertexArray tab;
    sf::Font font;
    sf::Text text[13][13];
    Pole poleGry[13][13];
    sf::Text ter;



public:

    Plansza();
    int liczby(int x,int y);
    void brak(int x,int y);
    void draw(sf::RenderWindow & win);
    int rysuj();
    int prawy(int x,int y, int p);
    void wolne();

};
Plansza::Plansza()
{
    int x,y;
    srand(time(NULL));
    for(int i=0; i<12; i++)
    {
        for(int j=0; j<12; j++)
        {
            poleGry[i][j].mina=0;
            poleGry[i][j].odkryte=0;
            poleGry[i][j].flaga=0;
        }
    }
    for(int j=0; j<=12; j++)
    {
        poleGry[0][j].mina=0;
        poleGry[11][j].mina=0;
        poleGry[j][0].mina=0;
        poleGry[j][11].mina=0;
        poleGry[0][j].odkryte=(-1);
        poleGry[11][j].odkryte=(-1);
        poleGry[j][0].odkryte=(-1);
        poleGry[j][11].odkryte=(-1);
    }

    for(int i=1; i<11; i++)
    {
        x=(rand()%10)+1;
        y=(rand()%10)+1;
        poleGry[x][y].mina=-1;
    }
}

int Plansza::prawy(int x,int y, int p)
{
    if(p==1)
    {
        poleGry[x][y].flaga=1;
        return 1;
    }
    if(p==-1)
    {
        poleGry[x][y].flaga=-1;
        return -1;
    }
    if(p==0)
    {
        poleGry[x][y].flaga=0;
        return 0;
    }
}

int Plansza::rysuj()
{
    font.loadFromFile("Arial.ttf");
    int xpos,ypos,n=0;
    tab.clear();
    sf::Color c;
    tab.setPrimitiveType(sf::Quads);
    for(int i=1; i<11; i++)
    {
        for(int j=1; j<11; j++)
        {

            if(poleGry[i][j].odkryte==1)
            {

                text[i][j].setFont(font);
                text[i][j].setColor(sf::Color::Red);
                char Cstr[20];
                sprintf(Cstr, "%i", liczby(i,j));
                text[i][j].setString(Cstr);
            }
            if(poleGry[i][j].odkryte==(-1))
            {
                text[i][j].setFont(font);
                text[i][j].setColor(sf::Color::Blue);
                text[i][j].setString("Przegrales! Kliknik ESC aby wyjsc");
            }
            xpos=25+j*50;
            ypos=25+i*50;
            if (poleGry[i][j].flaga==1&&poleGry[i][j].odkryte==0)
            {
                c=sf::Color::Green;

            }
            else
            {
                if (poleGry[i][j].odkryte==0)
                {
                    c=sf::Color::Black;
                }
                if (poleGry[i][j].odkryte==1)
                {
                    c =sf::Color::White;
                    text[i][j].setPosition(xpos+2,ypos-6);
                    n++;
                    if(n==90)
                    {
                        text[10][10].setFont(font);
                        text[10][10].setColor(sf::Color::Blue);
                        text[10][10].setString("Wygrales! Kliknik ESC aby wyjsc");
                        text[10][10].setPosition(0,0);
                    }
                }
                if (poleGry[i][j].odkryte==(-1))
                {
                    c=sf::Color::Red;
                    text[i][j].setPosition(0,0);
                }
            }

            tab.append(sf::Vector2f(xpos, ypos));         // wspolrzedne rogów prostokata
            tab.append(sf::Vector2f(xpos+25, ypos));
            tab.append(sf::Vertex(sf::Vector2f(xpos+25, ypos+25),c));
            tab.append(sf::Vertex(sf::Vector2f(xpos, ypos+25),c));

        }
    }
    return n;
}



int Plansza::liczby(int x, int y)
{
    int c=0;
    if(poleGry[x][y].flaga==1)
    {
        return 1;
    }
    if(poleGry[x][y].flaga==-1)
    {
        return 2;
    }
    if(poleGry[x][y].flaga==0)
    {
        if(poleGry[x+1][y].mina==1)
            c++;
        if(poleGry[x+1][y+1].mina==1)
            c++;
        if(poleGry[x+1][y-1].mina==1)
            c++;
        if(poleGry[x][y+1].mina==1)
            c++;
        if(poleGry[x][y-1].mina==1)
            c++;
        if(poleGry[x-1][y-1].mina==1)
            c++;
        if(poleGry[x-1][y].mina==1)
            c++;
        if(poleGry[x-1][y+1].mina==1)
            c++;
        if(poleGry[x][y].mina==1)
        {
            poleGry[x][y].odkryte=-1;
            return -1;
        }
        if(x==0||y==0)
            return -2;
        poleGry[x][y].odkryte=1;

        return c;
    }
}

void Plansza::brak(int x,int y)
{

    if(poleGry[x+1][y+1].odkryte==0&&liczby(x+1,y+1)==0)
        brak(x+1,y+1);
    if(poleGry[x][y+1].odkryte==0&&liczby(x,y+1)==0)
        brak(x,y+1);
    if(poleGry[x-1][y+1].odkryte==0&&liczby(x-1,y+1)==0)
        brak(x-1,y+1);
    if(poleGry[x+1][y].odkryte==0&&liczby(x+1,y)==0)
        brak(x+1,y);
    if(poleGry[x-1][y].odkryte==0&&liczby(x-1,y)==0)
        brak(x-1,y);
    if(poleGry[x-1][y-1].odkryte==0&&liczby(x-1,y-1)==0)
        brak(x-1,y-1);
    if(poleGry[x+1][y-1].odkryte==0&&liczby(x+1,y-1)==0)
        brak(x+1,y-1);
    if(poleGry[x][y-1].odkryte==0&&liczby(x,y-1)==0)
        brak(x,y-1);



}

void Plansza::draw(sf::RenderWindow & win)
{
    win.draw(tab);
    for(int i=1; i<11; i++)
    {
        for(int j=1; j<11; j++)
        {
            win.draw(text[i][j]);
        }
    }


}

int main()
{

    sf::RenderWindow window(sf::VideoMode(800, 600), "SFML window");
    sf::VertexArray linie(sf::Lines);
    sf::Text inst[10];
    sf::Font font;
    int x=-1,y=0, P[13][13];
    Plansza p;
    for(int i=0;i<11;i++)
    {
        for(int j=0;j<11;j++)
        {
            P[i][j]=0;
        }
    }

    while (window.isOpen())
    {
        sf::Event event;


        while (window.pollEvent(event))
        {
                {
                    if( event.type == sf::Event::Resized )
                         window.setSize( sf::Vector2u( 800, 600 ) );

                }
            if(x==-1)
            {
                font.loadFromFile("Arial.ttf");
                for(int i=0;i<4;i++)
                {
                    inst[i].setFont(font);
                    inst[i].setColor(sf::Color::Blue);
                    inst[i].setScale(0.5,0.5);
                }
                inst[0].setString("Instrukcja Klawiszy:");
                inst[0].setPosition(100,0);
                inst[1].setString("Lewy Przycisk Myszy - Odkrywanie Pol");
                inst[1].setPosition(0,30);
                inst[2].setString("Prawy Przycisk Myszy - Zaznaczanie Pol i odznaczanie ich,ale ich nie odkrywanie");
                inst[2].setPosition(0,60);
                inst[3].setString("Kliknij Spacje aby kontynuowac");
                inst[3].setPosition(0,90);
                if(sf::Keyboard::isKeyPressed(sf::Keyboard::Space))
                    x=0;
            }
            if(event.type == sf::Event::Closed||sf::Keyboard::isKeyPressed(sf::Keyboard::Escape))
                window.close();

            if(p.liczby(x,y)==-1)
            {
                p.rysuj();
                if (event.type == sf::Event::Closed||sf::Keyboard::isKeyPressed(sf::Keyboard::Escape))
                    window.close();
            }
            else
            {
                if(p.rysuj()==90)
                {
                    p.rysuj();
                    if (event.type == sf::Event::Closed||sf::Keyboard::isKeyPressed(sf::Keyboard::Escape))
                        window.close();
                }
                else
                {
                    p.rysuj();
                    if(p.liczby(x,y)==0)
                        p.brak(x,y);
                    if (event.type == sf::Event::MouseButtonPressed)
                    {
                        if(p.prawy(x,y,P[x][y])==(-1))
                        {
                            P[x][y]=0;
                            p.prawy(x,y,P[x][y]);
                        }
                        if (event.mouseButton.button == sf::Mouse::Left)
                        {
                            for(int i=1; i<11; i++)
                            {
                                for(int j=1; j<11; j++)
                                {

                                    int xpos=25+i*50;
                                    int ypos=25+j*50;
                                    if(event.mouseButton.x>=xpos&&event.mouseButton.x<=(xpos+25)&&event.mouseButton.y>=ypos&&event.mouseButton.y<=ypos+25)
                                    {
                                        x=j;
                                        y=i;
                                    }
                                }
                            }
                        }
                        else
                        {
                            if(event.mouseButton.button== sf::Mouse::Right)
                            {
                                for(int i=1; i<11; i++)
                                {
                                    for(int j=1; j<11; j++)
                                    {
                                        int xpos=25+i*50;
                                        int ypos=25+j*50;
                                        if(event.mouseButton.x>=xpos&&event.mouseButton.x<=(xpos+25)&&event.mouseButton.y>=ypos&&event.mouseButton.y<=ypos+25)
                                        {
                                            x=j;
                                            y=i;

                                            if(p.prawy(x,y,P[x][y])==1)
                                            {
                                                P[x][y]=-1;
                                                p.prawy(x,y,-1);
                                            }
                                            if(p.prawy(x,y,P[x][y])==0)
                                            {
                                                P[x][y]=1;
                                                p.prawy(x,y,P[x][y]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                p.rysuj();
            }
        }
        // Clear screen
        window.clear();
        for(int i=0;i<10;i++)
            if(x==-1)
                window.draw(inst[i]);
        if(x!=-1)
            p.draw(window);
        window.display();
    }
    return 0;
}
