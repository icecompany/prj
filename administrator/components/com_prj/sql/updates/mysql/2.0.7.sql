alter table `#__mkv_projects`
    add course_usd double(11,4) not null default 1 comment 'Курс доллара на конец проекта' after prefix,
    add course_eur double(11,4) not null default 1 comment 'Курс евро на конец проекта' after course_usd;
