import { Injectable } from "@angular/core"
import {HttpClient, HttpHeaders} from "@angular/common/http";
import 'rxjs/add/operator/map';
import {Observable} from "rxjs/Observable";

@Injectable()
export class OffersService {
    private restResourceURL : string = 'http://localhost:8000/offers';

    constructor(private http: HttpClient) {}

    provideRequestOptionsForJson() : object {
        return {
             headers : new HttpHeaders()
                 .set('Content-Type', 'application/json; charset=utf-8')
                 .set('Accept', 'application/json')
        };
    }

    /**
     * Method serves both scenarios for looking up with package+country
     * and the second by offer id. These both at the same time can not occur.
     * @param {any} packageId
     * @param {any} countryId
     * @param {any} offerId
     * @returns {Observable<Offers[]>}
     */
    getOffers(packageId = null, countryId = null, offerId = null) {
        let reqUrl = this.restResourceURL;
        // get filtered
        if(packageId != null && countryId != null) {
            reqUrl = [this.restResourceURL,'?package=',packageId,'&country=',countryId].join('');
        }
        // other scenario.
        if(offerId != null) {
            reqUrl = [this.restResourceURL,'?id=',offerId].join('');
        }
        return this.http.get(reqUrl)
            .map(response => response['data'])
    }

    createOffer(serializedFormData) {
        return this.http
            .post(this.restResourceURL, serializedFormData, this.provideRequestOptionsForJson())
            .map(response => response['success']);
    }

    updateOffer(serializedFormData) {
        return this.http
            .put(this.restResourceURL, serializedFormData, this.provideRequestOptionsForJson())
            .map(response => response['success']);
    }

    removeOffer(offerId) {
        return this.http
            .delete([this.restResourceURL,'?id=',offerId].join(''))
            .map(response => response['success']);
    }

}