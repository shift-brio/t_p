create database if not exists tp_db;
use tp_db;

/* tables */
create table if not exists tp_attachments (
   id int(20) AUTO_INCREMENT,
   link text,
   type text,
   meta text,
   added_at int(10),
   primary key(id)
);

create table if not exists tp_post (
   id int(20) AUTO_INCREMENT,
   usr_id int(20),
   title text,
   body text,
   attached text,
   identifier text,
   link text,
   country text,
   post_column text,
   added_at int(10),
   state int(1) default 0,
   primary key(id)
);

create table if not exists tp_share_with (
   id int(20) AUTO_INCREMENT,
   sharing text,
   identifier text,
   shared_to text,
   usr_id int(20),
   date_added int(10),
   primary key(id)
);

create table if not exists tp_ratings (
   id int(20) AUTO_INCREMENT,
   usr_id int(20),
   rating float,
   date_added int(10),
   primary key(id)
);

create table if not exists tp_support (
   id int(20) AUTO_INCREMENT,
   m_from int(20),
   m_to int(20),
   m_text text,
   m_date int(10),
   primary key(id)
);

create table if not exists tp_users (
   usr_id int(20) AUTO_INCREMENT,
   f_name text,
   l_name text,
   u_name text,
   u_email text,
   u_pass text,
   u_photo_name text,
   tp_mail text,
   country text,
   year text,
   joined text,
   primary key(usr_id)
);
 
 create table if not exists tp_circles (
   circ_id int(20) AUTO_INCREMENT,  
   usr_id_adding int(20),
   u_id_added int(20),
   date int(10),
   primary key(circ_id)
);

create table if not exists tp_post_views (
   id int(20) AUTO_INCREMENT,
   viewer int(20),
   identifier text,
   ip_address text,
   date int(10),
   primary key(id)
);

create table if not exists tp_likes (
   id int(20) AUTO_INCREMENT,
   usr_id int(20),
   post_identifier text,   
   added_at int(10),
   primary key(id)
);

create table if not exists tp_countries (
   id int(5) AUTO_INCREMENT,
   name text,
   date_added int(10),
   primary key(id)
);

create table if not exists logs (
   id int(20) AUTO_INCREMENT,
   ip text,
   user text,
   item text,
   type text,
   time int(10),
   primary key(id)
);

create table if not exists tp_profile_visits (
   id int(20) AUTO_INCREMENT,
   user int(20),
   viewer text,
   ip_address text,
   date int(10),
   primary key(id)
);

create table if not exists tp_share_stats (
   id int(20) AUTO_INCREMENT,
   identifier text,
   platform text,
   ip_address text,
   date int(10),
   primary key(id)
);

create table if not exists tp_notifications (
   id int(20) AUTO_INCREMENT,
   owner int(20),
   sender int(20),
   notification text,
   state int(1) default 0,
   type text,
   post_identifier text,
   time int(10),
   primary key(id)
);

create table if not exists tp_user_countries (
   id int(20) AUTO_INCREMENT,
   usr_id int(20),
   country text,
   state int(1) default 1,
   added_at int(10),
   primary key(id)
);

create table if not exists tp_comments (
   id int(20) AUTO_INCREMENT,
   usr_id int(20),
   post_identifier text,
   comment text,   
   c_date int(10),
   primary key(id)
);





/* create-table boiler plate
      create table if not exists TABLE_NAME (
         id int(20) AUTO_INCREMENT,

         added_at int(10),
         primary key(id)
      );
*/