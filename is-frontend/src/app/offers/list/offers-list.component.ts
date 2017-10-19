import { Component, OnInit } from '@angular/core';
import { Offers } from '../offers';
import { OffersService } from "../offers.service"
import { CountriesService } from "../../misc/countries.service";
import { PackagesService } from "../../packages/packages.service";
import { NgModel } from "@angular/forms"

@Component({
  selector: 'offers-component',
  templateUrl: './offers-list.component.html',
  styleUrls: ['./offers-list.component.css'],
})
export class OffersListComponent implements OnInit {
  title = 'Offers List';
  private results = [];
  private countiesList = [];
  private packagesList = [];
  // following properties has name as dummy param, we are not relying on. we need the ID!
  // we have set the ids of first records i know, these will be reset right when they load to first, anyways
  // these serving only as insurance for view to  not crash the app when id is undefined and view trying to access it.
  selectedCountry = {id:4,name:'the name'};
  selectedPackage = {id:80,name:'the name'};


  constructor(
      private offersService: OffersService,
      private countriesService: CountriesService,
      private packagesService: PackagesService
   ) {}

  ngOnInit() : void {
      this.showOffers();
      this.getCountriesDropdownData();
      this.getPackagesDropdownData();
  }

  editLink(offer : Offers) {
      return "/offers/" + offer.id + "/edit";
  }

  deleteLink(offer : Offers) {
      return "/offers/" + offer.id + "/delete"
  }

  addLink() {
      return "/offers/add"
  }

  filterOffer() {
      this.showOffers(this.selectedPackage.id, this.selectedCountry.id);
  }

  showOffers(packagedId = null, countryId = null) {
      return this.offersService.getOffers(packagedId, countryId)
          .subscribe(result => {
              this.results = result;
          });
  }

    /**
     * @desc drop down related
     */
  getCountriesDropdownData() {
      this.countriesService.getDropDownData().subscribe( result => {
          this.countiesList = result;
          this.selectedCountry = result[0];
      });
  }

    /**
     * @desc drop down related
     */
  getPackagesDropdownData() {
      this.packagesService.getPackagesDropdownData().subscribe( result => {
          this.packagesList = result;
          this.selectedPackage = result[0];
      });
  }
}
