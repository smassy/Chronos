use chronos;

-- Our super admin user
INSERT INTO `users` VALUES (1,'superadmin','$2y$10$ffCCStgfEZJjtg.qnmn2leeMHGlwgkpiLaoeeqjCMtK5oXQFyp1CC',255);
INSERT INTO departments (name) VALUES ('IT');
INSERT INTO user_details (user_id, department_id, last_name, first_name, email, title) VALUES (1,1,'Administrator','Super','root@localhost','root');
