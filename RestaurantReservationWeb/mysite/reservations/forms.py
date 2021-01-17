from django import forms
from django.contrib.auth.models import User
from django.contrib.auth.forms import UserCreationForm
from django.views.generic import ListView, DetailView, CreateView, UpdateView, DeleteView
from .models import Reservation, Restaurant
import datetime as dt

# class DateForm(forms.Form):
#     date = forms.DateTimeField(input_formats=['%d/%m/%Y %H:%M'])

class UserRegisterForm(UserCreationForm):
    email = forms.EmailField()
    class Meta:
        model = User
        fields = ['username', 'email', 'password1', 'password2']

class quickSearchForm(forms.ModelForm):
    class Meta:
        model = Reservation
        fields = ['num', 'datetime', 'restaurant']
    def clean(self):
        cleaned_data = super().clean()
        num = cleaned_data.get("num")
        datetime = cleaned_data.get("datetime")
        restaurant = cleaned_data.get("restaurant")
        availableTimeList = loopFunctionQuickSearch(restaurant, datetime, num)
        raise forms.ValidationError(availableTimeList)

class RestaurantRegisterForm(forms.ModelForm):
    class Meta:
        model = Restaurant
        fields = ['name', 'capacity', 'max_ppl', 'short_description', 'address']

class subForm(forms.ModelForm):
    class Meta: 
        model = Reservation
        fields = ['name', 'num', 'datetime', 'restaurant']
    def clean(self):
        cleaned_data = super().clean()
        reserve_name = cleaned_data.get("name")
        num = cleaned_data.get("num")
        datetime = cleaned_data.get("datetime")
        restaurant = cleaned_data.get("restaurant")
        #print(restaurant)
        people_counter, max_counter = loopFunction(restaurant, datetime)

        #print(people_counter, max_counter)

        capacity = Restaurant.objects.filter(name=restaurant).first().capacity
        max_ppl = Restaurant.objects.filter(name=restaurant).first().max_ppl

        if (people_counter + num) > capacity or (max_counter + num) > max_ppl:
            raise forms.ValidationError("Exceed the capacity of the restaurant, please choose another time")
        else:
            return cleaned_data

class updateSubForm(forms.ModelForm):
    class Meta: 
        model = Reservation
        fields = ['name', 'num', 'datetime', 'restaurant']
    # def save(self):
    #     self.num = self.initial['num']
    #     return self.num
    def clean(self):
        self.num = self.initial['num']
        cleaned_data = super().clean()
        reserve_name = cleaned_data.get("name")
        num = cleaned_data.get("num")
        datetime = cleaned_data.get("datetime")
        restaurant = cleaned_data.get("restaurant")
        #print(restaurant)
        numOld = self.num
        print(numOld)
        people_counter, max_counter = loopFunction(restaurant, datetime)

        #print(people_counter, max_counter)

        capacity = Restaurant.objects.filter(name=restaurant).first().capacity
        max_ppl = Restaurant.objects.filter(name=restaurant).first().max_ppl

        if (people_counter + num -numOld) > capacity or (max_counter + num-numOld) > max_ppl:
            raise forms.ValidationError("Exceed the capacity of the restaurant, please choose another time")
        else:
            return cleaned_data


def loopFunction(restaurantName, time_data):

    restaurant = restaurantName
    #print(restaurant)
    time_stamp = time_data
    # print(time_stamp)
    # print(time_stamp.year)
    # print(time_stamp.month)
    # print(time_stamp.day)
    start_time = time_stamp - dt.timedelta(hours=1)
    end_time = time_stamp + dt.timedelta(hours=1)
    # qSetTest = Restaurant.objects.filter(name=restaurant)
    # print(qSetTest)
    qSet1 = Reservation.objects.filter(restaurant=restaurant).filter(datetime__range=(start_time, end_time))
    qSet2 = Reservation.objects.filter(restaurant=restaurantName).filter(datetime__year=time_stamp.year).filter(datetime__month=time_stamp.month).filter(datetime__day=time_stamp.day)
    # print(qSet1)
    # print(qSet2)
    # capacity = rest.capacity
    # max_ppl = rest.max_ppl
    people_counter = 0
    max_counter = 0
    for record in qSet1:
        people_counter += record.num
    for record in qSet2:
        max_counter += record.num
    return people_counter, max_counter

def loopFunctionQuickSearch(restaurantName, time_data, num_people):
    numPpl = num_people
    restaurant = restaurantName
    time_stamp = time_data
    qSet1 = Reservation.objects.filter(restaurant=restaurant).filter(datetime__year=time_stamp.year).filter(datetime__month=time_stamp.month).filter(datetime__day=time_stamp.day)
    max_counter = 0
    availableTimeList = []
    for record in qSet1:
        max_counter += record.num
    if (max_counter + numPpl) >= Restaurant.objects.filter(name=restaurant).first().max_ppl:
        return "No Available Seat, Please Choose Another Day"
    for i in range(13):
        timeLabel = time_stamp.replace(hour=10, minute=0, second=0) + dt.timedelta(hours=i)
        startSearch = timeLabel - dt.timedelta(hours=1)
        endSearch = timeLabel + dt.timedelta(hours=1)
        people_counter = 0
        qSet2 = Reservation.objects.filter(restaurant=restaurant).filter(datetime__range=(startSearch, endSearch))
        for re in qSet2:
            people_counter += re.num
        if (people_counter + numPpl) <= Restaurant.objects.filter(name=restaurant).first().capacity:
            availableTimeList.append(timeLabel)
    return availableTimeList


# class ReservationCreateForm(forms.ModelForm):
#     # def __init__(self, rest_list, *args, **kwargs):
#     #     super(ReservationCreateForm, self).__init__(*args, **kwargs)
#     #     self.fields['restaurant'] = forms.ChoiceField(
#     #         choices=[o for o in rest_list]
#     #     )
#     reserve_name = forms.CharField(max_length=20, required=True)
#     num_people = forms.IntegerField(min_value=1, required=True)
#     date_time = forms.DateTimeField(required=True)
#     restaurant = forms.CharField(max_length=50, required=True)
#     class Meta:
#         model = Reservation
#         fields = [ 'name', 'num', 'datetime','restaurant' ]