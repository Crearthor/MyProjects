#include <SFML/Graphics.hpp>
#include <iostream>
#include <stdio.h>
#include <cstdlib>

using namespace std;

class Plansza
{
    sf::RectangleShape przyklad;
    sf::Text liczenie;
    sf::Text menu[100];
    sf::Text Porazka;
    sf::Font font[3];
public:
    sf::RectangleShape sciana[4];
    sf::RectangleShape tab;
    sf::RectangleShape waz[100];
    sf::RectangleShape punkt;
    int lmenu=0;
    int kmenu=0;
    int wielkosc=10;

    void draw(sf::RenderWindow & win, int n);
    int przegrana(sf::RenderWindow & win,sf::RenderWindow& win1, int n);
    void Poza();
    void punktacja(int pkt);
    void tablica_w(sf::RenderWindow&win);
    int ostart();
    int cofanie(int x,int y);
    int zmiany_w();

};


int Plansza::zmiany_w()
{
    if(ostart()==1)
    {
        if(sf::Keyboard::isKeyPressed(sf::Keyboard::Up)&& wielkosc<60)
        {

            wielkosc=wielkosc+1;
            while(800%wielkosc!=0||600%wielkosc!=0)
                wielkosc=wielkosc+1;

        }
        if(sf::Keyboard::isKeyPressed(sf::Keyboard::Down)&&wielkosc>10)
        {
            wielkosc=wielkosc-1;
            while(800%wielkosc!=0||600%wielkosc!=0)
                wielkosc=wielkosc-1;
        }



    }
    przyklad.setSize(sf::Vector2f (wielkosc,wielkosc));
    przyklad.setPosition(400,300);
    return wielkosc;
}
int Plansza::cofanie(int x,int y)
{
    sf::FloatRect snake=waz[0].getGlobalBounds();
    sf::FloatRect snake2=waz[1].getGlobalBounds();
    punkt.setFillColor(sf::Color::Red);
    if((snake.top+y==snake2.top)&&(snake.top-snake.height+y==snake2.top - snake2.height)&&(snake.left +x== snake2.left) && (snake.left+snake.width+x==snake2.left + snake2.width))
    {
        return 1;
    }
    return 0;
}

int Plansza::ostart()
{
    int j=0;
    font[0].loadFromFile("arial.ttf");
    for(int i=0;i<8;i++)
    {
        if(j==4)
            j=0;
        menu[i].setFont(font[0]);
        menu[i].setCharacterSize(20);
        menu[i].setPosition(0,j*20);
        j++;
    }
    menu[0].setString("Wybierz poziom trudnosci:");
    menu[1].setString("1.Poziom bez scian");
    menu[2].setString("2.Poziom ze scianami");
    menu[3].setString("3.Kliknij ESC aby wyjsc");

    menu[4].setString("Ustaw pozadana wielkosc gry:");
    menu[6].setString("Kliknij strzalke w gore aby zwiekszyc rozmiary obiektow");
    menu[7].setString("Kliknij strzalke w dol aby zmniejszyc rozmiary obiektow");



    if(sf::Keyboard::isKeyPressed(sf::Keyboard::Num2)&&lmenu==0)
    {
        lmenu=1;
        kmenu=3;
    }
    if(sf::Keyboard::isKeyPressed(sf::Keyboard::Num1)&&lmenu==0)
    {
        lmenu=1;
        kmenu=2;
    }

    if(sf::Keyboard::isKeyPressed(sf::Keyboard::Space)&&lmenu==1)
    {
        lmenu=kmenu;
    }
    return lmenu;

}


void Plansza::Poza()                                                            //co robi gdy waz wyjdzie poza okno
{
    int x;
    sf::FloatRect snake=waz[0].getGlobalBounds();
    if(snake.left>=800)
        waz[0].setPosition(0,snake.top);
    if(snake.top<0)
        waz[0].setPosition(snake.left,600);
    if(snake.left<0)
    {
        do
        {
            x=(rand()%wielkosc)+(800-wielkosc);
        }
        while((x%wielkosc)!=0);
        waz[0].setPosition(x,snake.top);
    }
    if(snake.top>=600)
        waz[0].setPosition(snake.left,0);
}

int Plansza::przegrana(sf::RenderWindow & win,sf::RenderWindow & win1,int n)      // porazka
{
    int a=1;
    sf::FloatRect snake=waz[0].getGlobalBounds();
    font[2].loadFromFile("arial.ttf");
    Porazka.setFont(font[2]);
    Porazka.setString("Przegrales!! Kliknij ESC aby wyjsc");
    Porazka.setPosition(200,250);

    for(int i=1; i<n; i++)
    {

        sf::FloatRect cialo=waz[i].getGlobalBounds();
        if((snake.top==cialo.top)&&(snake.top-snake.height==cialo.top - cialo.height)&&(snake.left == cialo.left) && (snake.left+snake.width==cialo.left + cialo.width))
        {

            win.draw(Porazka);
            win.display();
            do
            {
                    win.close();
                    win1.close();
            }while(a!=0);

        }
    }
    if(ostart()==3)
    {
        sf::FloatRect snake=waz[0].getGlobalBounds();
        if((snake.left+snake.width)>(800-wielkosc)||snake.top<(0+wielkosc)||snake.left<(0+wielkosc)||snake.top>(600-2*wielkosc))
        {
            win.draw(Porazka);
            win.display();
            do
            {
                if(sf::Keyboard::isKeyPressed(sf::Keyboard::Escape))
                {
                    win.close();
                    win1.close();
                    return 0;
                }
                 if(sf::Keyboard::isKeyPressed(sf::Keyboard::Space))
                {
                    return 1;
                }
            }while(a!=0);
        }

    }
}

void Plansza::punktacja(int pkt)                                                    //liczenie punktow
{
    font[1].loadFromFile("arial.ttf");
    char Cstr[20];
    sprintf(Cstr, "%i", pkt);
    liczenie.setFont(font[1]);

    liczenie.setString(Cstr);
    liczenie.setPosition(0,0);
}

void Plansza::tablica_w(sf::RenderWindow & win)                                    //wyswietlanie punktow
{
    win.clear();
    win.draw(liczenie);
    win.display();
}




void Plansza::draw(sf::RenderWindow & win,int n)                                       // rysowanie gry
{
    win.clear();
    if(ostart()==0)
    {
        for(int i=0;i<4;i++)
            win.draw(menu[i]);
    }
    if(ostart()==1)
    {
        zmiany_w();
        for(int i=4;i<=7;i++)
            win.draw(menu[i]);
        win.draw(przyklad);
    }
    if(ostart()==2)
    {
        win.draw(punkt);
        for(int i=0; i<n; i++)
            win.draw(waz[i]);

    }
    if(ostart()==3)
    {
        for(int i=0; i<n; i++)
        {
            win.draw(waz[i]);
            if(i<4)
                win.draw(sciana[i]);
        }

        win.draw(punkt);
    }
    win.display();
}
