create table LOOKS_PRODUCT_TB(
look_id int,
product_id varchar(20),
foreign key(product_id) references PRODUCT_INFO_TB (product_id),
foreign key(look_id) references LOOKS_INFO_TB (look_id),
primary key(look_id,product_id)
);


# ������ �������� ������ ����� alter table LOOKS_PRODUCT_TB add primary key(look_id,product_id);
#�� ��������ּ���