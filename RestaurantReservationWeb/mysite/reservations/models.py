from django.db import models
from django.contrib.auth.models import User
from django.utils import timezone
import datetime
from django.core.validators import MaxValueValidator, MinValueValidator
from django.urls import reverse
# Create your models here.

# permissions name
# booking_permission = ('_booking_', 'can make reservation')


# # Group names
# restaurant = '_restaurantUser_'
# customer = '_customerUser_'


class Restaurant(models.Model):
    name = models.CharField(max_length=50, unique=True, null=True)
    capacity = models.IntegerField(validators=[MinValueValidator(1)])
    max_ppl = models.IntegerField(validators=[MinValueValidator(1)])
    short_description = models.CharField(max_length=50, null=True)
    address = models.CharField(max_length=200, null=True, blank=True)

    def __str__(self):
        return self.name

class Manager(models.Model):
    user = models.OneToOneField(User, on_delete=models.CASCADE)
    restaurant = models.ForeignKey(Restaurant, on_delete=models.CASCADE)

    def __str__(self):
        return self.user.get_username()

class Customer(models.Model):
    user = models.OneToOneField(User, on_delete=models.CASCADE)

    def __str__(self):
        return self.user.get_username()

class Reservation(models.Model):
    name = models.CharField(max_length=20)
    num = models.IntegerField(default=2)
    datetime = models.DateTimeField(default=timezone.now)
    customer = models.ForeignKey(Customer, on_delete=models.CASCADE)
    restaurant = models.ForeignKey(Restaurant, on_delete=models.CASCADE)
    
    def __str__(self):
        return self.name + " for " + str(self.num) + " at " + str(self.datetime) + " in " + str(self.restaurant)
    def get_absolute_url(self):
        return reverse('reservation_details', kwargs={'pk': self.pk})