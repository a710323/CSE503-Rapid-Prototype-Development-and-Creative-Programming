# CSE330
443901

466303

[News-Sharing-Website](http://ec2-3-16-22-100.us-east-2.compute.amazonaws.com/~alexteng/module3/module3-443901-466303/mainpage.php)

Default Username and Password: 

news_default

# Creative Portion:

1.  User Profile Page: 

  - Register user can see his/her own user page, containing the stories and comments posted by him/her. 
  
  - This user profile page displays all the posts and comments made by the current user so that the user can review his/her posts before editing or deleting them.
  
  - When editing a post, the user can see the details of the post that he/she originally posted as a reference.

  - Edit and delete are also implemented in this page.

2.  Keyword and Search function:

  - When registered user upload a story, he/she will be required to include the title, link, commentary, and at least 1 keyword.

  - All users, registered and unregistered, can search post by title, username, postid, or keywords.
  
  - When searching, we used the "LIKE" statement so that a post can be searched just by using a part of the username, keyword, or title. For example, if there is a post with the title "Cookie Monster", searching for "Cookie" will show this post.

3.  Security Question and Reset Password:

  - If a registered user forget the password, he/she can use username and security question to reset the password.
  
  - When resetting the password, not entering the same new password twice will throw an error and redirect the user to the log-in page instead of the reset password page for extra security.
