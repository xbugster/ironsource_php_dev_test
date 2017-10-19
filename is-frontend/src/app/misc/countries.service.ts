import { Injectable } from "@angular/core"
import {HttpClient} from "@angular/common/http";
import 'rxjs/add/operator/map';

@Injectable()
export class CountriesService {
    private countriesUrl : string = 'http://localhost:8000/get_countries_dropdown';

    constructor(private http: HttpClient) {}

    getDropDownData() {
        return this.http.get(this.countriesUrl)
            .map(response => response['data'])
    }
}