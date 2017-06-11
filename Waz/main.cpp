#include <SFML/Graphics.hpp>
#include <iostream>
#include <stdio.h>
#include <cstdlib>
#include "obiekty.h"
using namespace std;



int main()
{
     gracz p;

    srand(time(NULL));

    sf::RenderWindow punkty(sf::VideoMode(100,50),"Punktacja");             // okno z punktacja
    sf::Clock zegar;
    sf::RenderWindow window(sf::VideoMode(800, 600),"Gra") ;                    //okno z gra
    sf::Time odswiezanie=sf::Time::Zero;
    const sf::Time czas=sf::seconds(1.f/30.f);
    int ruch=0,start=0,n=5;

    punkty.setPosition(sf::Vector2i(800,0));

    while (window.isOpen())
    {
        sf::Time Czas = zegar.restart();
        odswiezanie += Czas;
        while(odswiezanie > czas)
        {
            odswiezanie -= czas;
            odswiezanie -= czas;
            sf::Event event;
            while (window.pollEvent(event))
            {
                if(sf::Keyboard::isKeyPressed(sf::Keyboard::Escape)||event.type == sf::Event::Closed)
                    window.close();
            }

            window.setPosition(sf::Vector2i(100, 90));

            if(p.ostart()>=2&&start==0)
            {
                p.postac(n);
                p.kwadrat();
                p.sciany();
                start=1;
            }

            if(p.ostart()>1)
            {
                p.przegrana(window,punkty,n);
                if((sf::Keyboard::isKeyPressed(sf::Keyboard::Up)||ruch==1)&&ruch!=2&&p.cofanie(0,-p.zmiany_w())==0)
                {

                    p.przesun(sf::Vector2f (0,-p.zmiany_w()),n);
                    ruch=1;

                    if(event.key.code==sf::Keyboard::Left&&p.cofanie(-p.zmiany_w(),0)==0)
                    {
                        ruch=3;

                    }
                    if((event.key.code==sf::Keyboard::Right)&&p.cofanie(p.zmiany_w(),0)==0)
                    {
                        ruch=4;
                    }
                }else
                if((sf::Keyboard::isKeyPressed(sf::Keyboard::Down)||ruch==2)&&ruch!=1&&p.cofanie(0,p.zmiany_w())==0)
                {
                    ruch=2;
                    p.przesun(sf::Vector2f (0,p.zmiany_w()),n);

                    if(event.key.code==sf::Keyboard::Left&&p.cofanie(-p.zmiany_w(),0)==0)
                    {
                        ruch=3;
                    }
                    if((event.key.code==sf::Keyboard::Right)&&p.cofanie(p.zmiany_w(),0)==0)
                    {
                        ruch=4;
                    }
                }else
                if((sf::Keyboard::isKeyPressed(sf::Keyboard::Left)||ruch==3)&&ruch!=4&&ruch!=0&&p.cofanie(-p.zmiany_w(),0)==0)
                {

                    ruch=3;
                    p.przesun(sf::Vector2f (-p.zmiany_w(),0),n);

                    if(event.key.code==sf::Keyboard::Up&&p.cofanie(0,-p.zmiany_w())==0)
                    {
                        ruch=1;
                    }
                    if((event.key.code==sf::Keyboard::Down)&&p.cofanie(0,p.zmiany_w())==0)
                     {
                         ruch=2;
                     }
                }else
                if((sf::Keyboard::isKeyPressed(sf::Keyboard::Right)||ruch==4)&&p.cofanie(p.zmiany_w(),0)==0)
                {


                    ruch=4;
                    p.przesun(sf::Vector2f (p.zmiany_w(),0),n);


                    if(event.key.code==sf::Keyboard::Up&&ruch!=2&&p.cofanie(0,-p.zmiany_w())==0)
                    {
                        ruch=1;

                    }
                    if((event.key.code==sf::Keyboard::Down)&&ruch!=1&&p.cofanie(0,p.zmiany_w())==0)
                     {
                         ruch=2;

                     }
                }
                n=p.zdobycie(n);
                p.punktacja(n-5);
                p.tablica_w(punkty);

            }
            if(p.ostart()==2)
            {
                p.Poza();
            }

        }
        p.tablica_w(punkty);
        p.draw(window,n);

    }
    return 0;
}
