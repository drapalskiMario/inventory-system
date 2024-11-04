import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms'
import { HttpClient } from '@angular/common/http';
import { lastValueFrom } from 'rxjs';

@Component({
  selector: 'app-customers',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './customers.component.html',
  styleUrl: './customers.component.css'
})
export class CustomersComponent {
  private apiUrl = 'http://localhost:3000/api/customers'

  customers: any[] = [];
  showingList: boolean = true;
  showingForm: boolean = false;
  newCustomer: any = { id: null, name: '', email: '' };
  isEditing: boolean = false;

  constructor(private http: HttpClient) { }

  async ngOnInit() {
    const source = this.http.get(this.apiUrl);
    const { customers } = await lastValueFrom(source) as any
    this.customers = customers;
  }

  showList() {
    this.showingList = true;
    this.showingForm = false;
  }

  showForm() {
    this.showingList = false;
    this.showingForm = true;

  }

  async saveCustomer() {
    if (this.isEditing) {
      const index = this.customers.findIndex(c => c.id === this.newCustomer.id);
      const source = this.http.put(`${this.apiUrl}/${this.newCustomer.id}`, { ...this.newCustomer });
      await lastValueFrom(source)
      this.customers[index] = { ...this.newCustomer };
    } else {
      const source = this.http.post(this.apiUrl, { ...this.newCustomer });
      const res = await lastValueFrom(source) as any;
      this.newCustomer.id = res.customer.id;
      this.customers.push(this.newCustomer)
    }
    this.showList();
  }

  editCustomer(customer: any) {
    this.isEditing = true;
    this.newCustomer = { ...customer };
    this.showForm();
  }

  async deleteCustomer(id: number) {
    const source = this.http.delete(`${this.apiUrl}/${id}`);
    await lastValueFrom(source)
    const index = this.customers.findIndex(c => c.id === this.newCustomer.id);
    this.customers.splice(index, 1);
  }
}