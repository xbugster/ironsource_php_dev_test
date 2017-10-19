import {Injectable} from "@angular/core"
import {HttpClient, HttpHeaders} from "@angular/common/http";
import 'rxjs/add/operator/map';
import {Packages} from "./packages";
import {Observable} from "rxjs/Observable";

@Injectable()
export class PackagesService {
    private restResourceURL : string = 'http://localhost:8000/packages';
    private packagedDropdownDataUrl : string = 'http://localhost:8000/get_packages_for_dropdown';

    constructor(private http: HttpClient) {}

    getPackages(id = null) {
        let reqUrl = this.restResourceURL;
        if (id != null) {
            reqUrl = this.restResourceURL + '?id=' + id;
        }
        return this.http.get(reqUrl)
            .map(response => response['data'])
    }

    getPackagesDropdownData() {
        return this.http.get(this.packagedDropdownDataUrl)
            .map(response => response['data'])
    }

    createPackage(serializedFormData) {
        return this.http
            .post(this.restResourceURL, serializedFormData, this.provideRequestOptionsForJson())
            .map(response => response['success']);
    }

    removePackage(offerId) {
        return this.http
            .delete(this.restResourceURL + '?id=' + offerId)
            .map(response => response['success']);
    }

    updatePackage(serializedData) {
        return this.http
            .put(this.restResourceURL, serializedData, this.provideRequestOptionsForJson())
            .map(response => response['success']);
    }

    provideRequestOptionsForJson() : object {
        return {
            headers : new HttpHeaders()
                .set('Content-Type', 'application/json; charset=utf-8')
                .set('Accept', 'application/json')
        };
    }
}