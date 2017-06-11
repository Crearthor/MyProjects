#include <SFML/Graphics.hpp>
#include <iostream>
#include <stdio.h>
#include <cstdlib>
#include "system.h"
using namespace std;

class gracz
:public Plansza
{
    //sf::VertexArray tab;



public:


    void postac(int n);
    void przesun(sf::Vector2f wektor, int n);
    void sciany();
    void kwadrat();
    int zdobycie(int n);


};

void gracz::sciany()
{
    for(int i=0;i<4;i++)
    {
        if(i<2)
            sciana[i].setSize(sf::Vector2f(wielkosc, 600));
        else
            sciana[i].setSize(sf::Vector2f(800, wielkosc));
        sciana[i].setFillColor(sf::Color::White);
    }
    sciana[0].setPosition(0,0);
    sciana[2].setPosition(0,0);
    sciana[1].setPosition(800-wielkosc,0);
    sciana[3].setPosition(0,600-wielkosc);


}
void gracz::kwadrat()
{
    punkt.setSize(sf::Vector2f (wielkosc,wielkosc));
    int x,y;
    if(ostart()==2)
    {
        do
        {
            x=(rand()%800)+0;
            y=(rand()%600)+0;
        }
        while((x%wielkosc)!=0 || (y%wielkosc)!=0);
    }
    if(ostart()==3)
    {
          do
        {
            x=(rand()%(800-2*wielkosc))+wielkosc;
            y=(rand()%(600-2*wielkosc))+wielkosc;
        }
        while((x%wielkosc)!=0 || (y%wielkosc)!=0);
    }
    punkt.setPosition(x,y);
}
int gracz::zdobycie(int n)                                                         //tworzenie nowego celu
{

    sf::FloatRect snake=waz[0].getGlobalBounds();
    sf::FloatRect cel=punkt.getGlobalBounds();
    punkt.setFillColor(sf::Color::Red);
    if((snake.top==cel.top)&&(snake.top-snake.height==cel.top - cel.height)&&(snake.left == cel.left) && (snake.left+snake.width==cel.left + cel.width))
    {
        kwadrat();
        for(int i=1;i<n;i++)
        {
            sf::FloatRect snake1=waz[i].getGlobalBounds();
            if((snake1.top==cel.top)&&(snake1.top-snake1.height==cel.top - cel.height)&&(snake1.left == cel.left) && (snake1.left+snake1.width==cel.left + cel.width))
                zdobycie(n);
        }

        n++;
    }
    return n;
}

void gracz::przesun(sf::Vector2f wektor,int n)                                    //poruszanie sie weza
{
    sf::FloatRect snake=waz[0].getGlobalBounds();
    for(int i=n; i>0; i--)
    {
        waz[i]=waz[i-1];

    }
    waz[0].move(wektor);
    if(ostart()==3&&(snake.left>790||snake.top<10||snake.left<10||snake.top>590))
        waz[0].setFillColor(sf::Color::White);
}

void gracz::postac(int n)                          //tworzenie postaci
{
    int xpos[100];

    xpos[n]=wielkosc;

    tab.setSize(sf::Vector2f (wielkosc,wielkosc));
    for(int i=n; i!=-1; i--)
    {
        waz[i]=tab;
        xpos[i-1]=xpos[i]+wielkosc;
        waz[i].setPosition(xpos[i],wielkosc);
        waz[i].setFillColor(sf::Color::Green);
    }

}

