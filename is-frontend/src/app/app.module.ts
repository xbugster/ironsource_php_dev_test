import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { ReactiveFormsModule,FormsModule }   from '@angular/forms';
import { RouterModule }   from '@angular/router';
import { HttpClientModule } from "@angular/common/http"

// Services
import { PackagesService } from "./packages/packages.service";
import { OffersService } from "./offers/offers.service"; // +service

// components
import { AppComponent } from './app.component';
import { MenuComponent } from "./menu/menu.component";
import { HomeComponent } from "./home/home.component"
import { GenerateOffersComponent } from "./misc/generate-offers.component";
import { GeneratePackageFilesComponent } from "./misc/generate-package-files.component";
// packages related components
import { PackagesListComponent } from './packages/list/packages-list.component';
import { PackageDeleteComponent } from "./packages/delete/package-delete.component";
import { PackageEditComponent } from "./packages/edit/package-edit.component"
import { PackageAddComponent } from "./packages/add/package-add.component"
// offers related components
import { OffersListComponent } from './offers/list/offers-list.component';
import { OfferAddComponent } from "./offers/add/offer-add.component";
import { OfferEditComponent } from "./offers/edit/offer-edit.component";
import {  OfferDeleteComponent } from "./offers/delete/offer-delete.component";
import {MiscService} from "./misc/misc.service";
import {CountriesService} from "./misc/countries.service";

@NgModule({
    declarations: [
        AppComponent,
        MenuComponent,
        HomeComponent,
        GenerateOffersComponent,
        GeneratePackageFilesComponent,
        PackagesListComponent,
        PackageDeleteComponent,
        PackageAddComponent,
        PackageEditComponent,
        OffersListComponent,
        OfferAddComponent,
        OfferEditComponent,
        OfferDeleteComponent
    ],
    providers: [
        PackagesService,
        OffersService,
        MiscService,
        CountriesService
    ],
    bootstrap: [AppComponent],
    imports: [
        BrowserModule,
        FormsModule,
        ReactiveFormsModule,
        HttpClientModule,
        RouterModule.forRoot([
            {
                path: '',
                component: HomeComponent
            },
            {
                path: 'packages',
                children: [
                    { path: '', redirectTo: 'list', pathMatch: 'full' },
                    { path: 'list', component: PackagesListComponent },
                    { path: 'add', component: PackageAddComponent },
                    {
                        path: ':id',
                        children: [
                            { path: '', redirectTo: 'edit', pathMatch: 'full' },
                            { path: 'edit', component: PackageEditComponent },
                            { path: 'delete', component: PackageDeleteComponent },
                        ]
                    },
                ]
            },
            {
                path: 'offers',
                children: [
                    { path: '', redirectTo: 'list', pathMatch: 'full' },
                    { path: 'list', component: OffersListComponent },
                    { path: 'add', component: OfferAddComponent },
                    {
                        path: ':id',
                        children: [
                            { path: '', redirectTo: 'edit', pathMatch: 'full' },
                            { path: 'edit', component: OfferEditComponent },
                            { path: 'delete', component: OfferDeleteComponent },
                        ]
                    },
                ]
            },
            {
                path: 'generate_offers',
                component: GenerateOffersComponent
            },
            {
                path: 'generate_package_files',
                component: GeneratePackageFilesComponent
            }
        ])
    ]
})

export class AppModule { }
