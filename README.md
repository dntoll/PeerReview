# Description
Simple Peer Review system for use in courses. Provided as is, but with an intention to adopt to a multi-course system.

## Installation

  * git clone https://github.com/dntoll/PeerReview.git
  * create directories:
    * /data
    * /data/2dv610
    * /data/2dv610/Users
    * /data/2dv610/Review
    * /data/2dv610/ReviewFeedback
  * git pull origin master
  * vagrant up
  * vagrant ssh
    * sudo nano /etc/nginx/conf.d/default.conf
    * change "root   /var/www/default;" to: "root   /vagrant;"
    * CTRL+x, Y (save)
    * sudo service nginx restart
    * exit
  * ... edit course/2dv610/settings ...

Two users in the system

Teacheraccount "admin1" with its personal link
http://localhost:8089/index.php?userID=12724401e9a2e2bd7142972906ae6643

Studentaccount "user1" with its personal link
http://localhost:8089/index.php?userID=b5cc34abc6e343f267b3cd934d2aa622


##TODO


2. Teachers should be able to flag inappropriate Testplans, Reviews and Feedback posts.
3. Availability of feedback and reviews should show on menu...
4. Grade should show grounds for grade...
6. Grading should be ?

7. Ställa in review-source-type i settings. Kolla efter det på rätt ställe(n)
8. Ändra klasser från TestPlan till ReviewSource?
