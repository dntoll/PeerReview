# Description
Simple Peer Review system for use in courses. Provided as is, but with an intention to adopt to a multi-course system.

## Installation
  
  * git clone https://github.com/dntoll/PeerReview.git
  * ... create data folder and perhaps subfolders
   * mkdir data
  * vagrant up
  * vagrant ssh
    * sudo nano /etc/nginx/conf.d/default.conf
    * change to: root   /vagrant;
    * sudo service nginx restart
  * ... edit course/2dv610/settings ...

##TODO


2. Teachers should be able to flag inappropriate Testplans, Reviews and Feedback posts.
3. Availability of feedback and reviews should show on menu...
4. Grade should show grounds for grade...
6. Grading should be ?
