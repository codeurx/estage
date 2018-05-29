import { Component, OnInit } from '@angular/core';
import { AuthService } from 'src/app/auth.service';
import { Title } from '@angular/platform-browser';
import { Router } from '@angular/router';
@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {
  authenticated = false;
  constructor(private auth:AuthService, private titleService:Title,private router:Router) { }
  public setTitle( newTitle: string) {
    this.titleService.setTitle(newTitle);
  }
  ngOnInit() {
    this.auth.isLoggedIn().subscribe(data =>{
      this.authenticated = data.isAuth;
    });
    if(!this.authenticated){
      this.router.navigate(['Auth'])
    }
    this.setTitle('Acceuil')
  }
}
