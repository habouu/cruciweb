create table if not exists user(
    id int auto_increment primary key,
    username varchar(30) unique not null,
    email varchar(180) unique not null,
    password varchar(255) not null,
    role enum('admin', 'registered') default 'registered',
    craated_at timestamp default current_timestamp
);

create table grid(
    id int auto_increment primary key,
    name varchar(30) unique not null,
    nb_row int not null,
    nb_col int not null,
    level enum('easy', 'medium', 'hard') not null,
    created_at timestamp default current_timestamp,
    user_id int not null,
    constraint fk_grid_user foreign key (user_id) references user(id) on delete cascade
);

create table cell(
    id int auto_increment primary key,
    num_row int not null,
    num_col char(1) not null,
    letter char(1) default null,
    type enum('white', 'black') default 'white',
    grid_id int not null,
    constraint fk_cell_grid foreign key (grid_id) references grid(id) on delete cascade
);

create table definition(
    id int auto_increment primary key,
    start_num_row int not null,
    start_num_col char(1) not null,
    end_num_col char(1) not null,
    end_num_row int not null,
    content varchar(255) not null,
    direction enum('horizontal', 'vertical') not null,
    grid_id int not null,
    constraint fk_definition_grid foreign key (grid_id) references grid(id) on delete cascade
);

create table save(
    id int auto_increment primary key,
    row int not null,
    col char(1) not null,
    letter char(1) default null,
    created_at timestamp default current_timestamp,
    user_id int not null,
    grid_id int not null,
    constraint fk_save_user foreign key (user_id) references user(id) on delete cascade,
    constraint fk_save_grid foreign key (grid_id) references grid(id) on delete cascade
);

insert into user (username, email, password, role) values ('admin', 'admin@gmail.com', sha2('admin123', 256), 'admin');
