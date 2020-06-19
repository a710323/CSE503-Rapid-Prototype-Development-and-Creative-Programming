from django.http import HttpResponse
from django.contrib import messages
from django.contrib.auth import login, authenticate
from django.contrib.auth.forms import UserCreationForm
from django.shortcuts import render, redirect
from .templates.reservations import *
from .models import Restaurant, Customer, Reservation
from .forms import UserRegisterForm, subForm, quickSearchForm, RestaurantRegisterForm, updateSubForm
from django.contrib.auth.decorators import login_required 
from django.views.generic import ListView, DetailView, CreateView, UpdateView, DeleteView
from django.contrib.auth.mixins import LoginRequiredMixin, UserPassesTestMixin
from django.urls import reverse, reverse_lazy



# Create your views here.
# Just for the test run on server
 
# Create your views here.
def home(request):
    context = {
        'restaurant' : Restaurant.objects.all()
    }
    return render(request, 'reservations/home.html', context)

class ReservationDetailView(LoginRequiredMixin,UserPassesTestMixin, DetailView):
    model = Reservation
    template_name="reservations/reservation_details.html"
    context_object_name = 'reservations'
    def test_func(self):
        reserve = self.get_object()
        u = self.request.user
        c = Customer.objects.filter(user_id=u.id).first()
        if c == reserve.customer:
            return True
        else:
            return False


class ReservationCreateQuickView(LoginRequiredMixin, CreateView):
    model = Reservation
    form_class = quickSearchForm
    template_name="reservations/reservation_quickSearch.html"
    fields = ['num', 'datetime']
    def get_form(self, form_class=quickSearchForm):
        form = super(ReservationCreateQuickView,self).get_form(form_class=quickSearchForm)
        form.fields['datetime'].widget.attrs.update({'id':'datetimepicker'})
        return form

    def form_valid(self, form):
        u = self.request.user
        c = Customer.objects.filter(user_id=u.id).first()
        form.instance.customer = c
        return super().form_valid(form)


class ReservationUpdateView(LoginRequiredMixin, UpdateView):
    model = Reservation
    form_class = updateSubForm
    template_name = "reservations/reservation_update.html"
    fields = ['name', 'num', 'datetime', 'restaurant']
    # def get_absolute_url(self):
    #     return reverse('profile', kwargs={'pk': self.object.id})
    def get_form(self, form_class=updateSubForm):
        form = super(ReservationUpdateView,self).get_form(form_class=updateSubForm)
        form.fields['datetime'].widget.attrs.update({'id':'datetimepicker'})
        return form
    def form_valid(self, form):
        u = self.request.user
        c = Customer.objects.filter(user_id=u.id).first()
        form.instance.customer = c
        return super().form_valid(form)
    def test_func(self):
        reserve = self.get_object()
        u = self.request.user
        c = Customer.objects.filter(user_id=u.id).first()
        if c == reserve.customer:
            return True
        else:
            return False


class ReservationDeleteView(LoginRequiredMixin,UserPassesTestMixin, DeleteView):
    model = Reservation
    template_name="reservations/reservation_confirm_delete.html"
    context_object_name = 'reservations'
    success_url = "/home"
    def test_func(self):
        reserve = self.get_object()
        u = self.request.user
        c = Customer.objects.filter(user_id=u.id).first()
        if c == reserve.customer:
            return True
        else:
            return False

class extendCreateView(CreateView):
    model = Reservation
    form_class = subForm
    template_name='reservations/reservation_create.html'
    #fields = ['name', 'num', 'datetime', 'restaurant']
    def get_form(self, form_class=subForm):
        form = super(extendCreateView,self).get_form(form_class=subForm)
        form.fields['datetime'].widget.attrs.update({'id':'datetimepicker'})
        return form
    def form_valid(self, form):
        u = self.request.user
        c = Customer.objects.filter(user_id=u.id).first()
        form.instance.customer = c
        # a,b = loopFunction(rest_name, time_reserve)
        return super().form_valid(form)

def register(response):
    if response.method == "POST":
        form = UserRegisterForm(response.POST)
        if form.is_valid():
            form.save()
            username = form.cleaned_data.get('username')
            messages.success(response, f'Account created for {username}')
            return redirect('login')
        else:
            form.clean()
    else:
        form = UserRegisterForm()
    return render(response, 'reservations/register.html', {'form' : form})

@login_required
def profile(request):
    u = request.user
    uid = u.id
    c = Customer.objects.filter(user_id=uid).first()
    user_reserve = Reservation.objects.filter(customer_id = c.id)
    return render(request, 'reservations/profile.html', {'reservationList': user_reserve})

def restaurants(response):
    if response.method == "GET":
        searchkey = response.GET.get("searchkey")
        if response.GET.get("search"):
            if len(searchkey) < 1:
                restaurants = Restaurant.objects.all()
            else:
                restaurants = Restaurant.objects.filter(name__icontains=searchkey)
        else:
            searchkey = ""
            restaurants = Restaurant.objects.all()

    return render(response, 'reservations/restaurants.html', {"restaurants": restaurants, "searchkey": searchkey})

def restaurant_details(response, id):
    rest = Restaurant.objects.get(id=id)
    #reservations = rest.reservation_set.all()
    return render(response, 'reservations/restaurant_details.html', {"rest" : rest})

def new_restaurant(response):
    if response.method == "POST":
        form = RestaurantRegisterForm(response.POST)
        if form.is_valid():
            form.save()
            return redirect('login')
    else:
        form = RestaurantRegisterForm()
    return render(response, 'reservations/newrestaurant.html', {'form':form})
