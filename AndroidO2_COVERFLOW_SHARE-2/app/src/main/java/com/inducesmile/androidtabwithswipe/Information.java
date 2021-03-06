package com.inducesmile.androidtabwithswipe;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.os.StrictMode;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.widget.AdapterView;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import org.xmlpull.v1.XmlPullParser;
import org.xmlpull.v1.XmlPullParserFactory;

import java.io.IOException;
import java.io.InputStream;
import java.net.URL;
import java.util.ArrayList;



/**
 * Created by User on 2016-08-09.
 */
public class Information extends Activity implements View.OnClickListener {
    String SERVER_ADDRESS;
    TextView mText;
    TextView mText2;
    ArrayList<MyInfoItem> arItem2;

    ArrayList<String> product_idlist;
    ArrayList<String> namelist;
    ArrayList<String> pricelist;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        requestWindowFeature(Window.FEATURE_NO_TITLE);
        /*getWindow().setFlags(WindowManager.LayoutParams.FLAG_BLUR_BEHIND,
                WindowManager.LayoutParams.FLAG_BLUR_BEHIND);*/

        /*Window win = getWindow();
        win.setLayout(ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        win.setGravity(Gravity.CENTER);*/

        setContentView(R.layout.listview3);

        arItem2 = new ArrayList<MyInfoItem>();
        MyInfoItem mi2;
        mi2 = new MyInfoItem("  Facebook Share"); arItem2.add(mi2);
        mi2 = new MyInfoItem("  구성 및 세탁 방법"); arItem2.add(mi2);
        mi2 = new MyInfoItem("  치수 안내"); arItem2.add(mi2);
        mi2 = new MyInfoItem("  카트에 추가"); arItem2.add(mi2);
        mi2 = new MyInfoItem("  카트 아이템 보기"); arItem2.add(mi2);

        MyInfoListAdapter MyAdapter = new MyInfoListAdapter(this, R.layout.custom, arItem2);

        ListView MyList;
        MyList=(ListView)findViewById(R.id.list);
        MyList.setAdapter(MyAdapter);
        MyList.setOnItemClickListener(mItemClickListener);

        MyApplication myApp = (MyApplication) getApplication();
        SERVER_ADDRESS = myApp.getSERVER_ADDRESS();

        mText = (TextView)findViewById(R.id.name);
        mText2 = (TextView)findViewById(R.id.product_id);

        StrictMode.enableDefaults();
        InputStream is = null;
        try {
            mText.setText("");

            URL url = new URL(SERVER_ADDRESS + "/product_search.php?look_id=" + myApp.getGlobalValue3());
            is = url.openStream(); //서버의 product_search.php파일을 실행함

            namelist = getXmlDataList("product_search_result.xml", "name"); //name 태그값을 읽어 prica 리스트에 저장
            product_idlist = getXmlDataList("product_search_result.xml", "product_id");//product_id 태그값을 읽어 namelist 리스트에 저장
            pricelist = getXmlDataList("product_search_result.xml", "price"); //price 태그값을 읽어 prica 리스트에 저장

            if (product_idlist.isEmpty())
                mText.setText("아무것도 검색되지 않았습니다.");
            else {
                mText.setText(namelist.get(myApp.getGlobalValue2()));
                mText2.setText("상품번호 " + product_idlist.get(myApp.getGlobalValue2()));


            }
        } catch (Exception e) {
            Log.e("Error", ""+ e.getMessage());
        } finally { try {
            is.close();
        } catch (IOException e) {
            // Swallow
        }


        }
    }



    AdapterView.OnItemClickListener mItemClickListener =
            new AdapterView.OnItemClickListener() {
                public void onItemClick(AdapterView<?> parent, View view,
                                        int position, long id) {
                    Intent intent;
                    switch (position) {
                        case 0:
                            intent = new Intent (getApplicationContext(), FacebookPhoto.class);
                            startActivity(intent);
                            break;
                        case 1:
                            intent = new Intent(getApplicationContext(), PopupConsist.class);
                            startActivity(intent);
                            break;
                        case 2:
                            intent = new Intent(getApplicationContext(), PopupSize.class);
                            startActivity(intent);
                            break;
                        case 3:
                            if (product_idlist.isEmpty())
                            {

                            }
                            else
                            {
                                CartItemContainer.getInstance().AddToCart();
                                Toast.makeText(Information.this, "해당 상품이 카트에 추가되었습니다!", Toast.LENGTH_SHORT).show();
                            }

                            // intent = new Intent(getApplicationContext(), BookMarkActivity.class);
                            // startActivity(intent);
                            break;
                        case 4:
                            intent = new Intent(getApplicationContext(), BookMarkActivity.class);
                            startActivity(intent);
                            break;
                    }
                }
            };

    private ArrayList<String> getXmlDataList(String filename, String str) { //태그값 여러개를 받아오기위한 ArrayList<String>형 변수
        String rss = SERVER_ADDRESS+"/";
        ArrayList<String> ret = new ArrayList<String>();

        try { //XML 파싱을 위한 과정
            XmlPullParserFactory factory = XmlPullParserFactory.newInstance();
            factory.setNamespaceAware(true);
            XmlPullParser xpp = factory.newPullParser();
            URL server = new URL(rss + filename);
            InputStream is = server.openStream();
            xpp.setInput(is, "UTF-8");

            int eventType = xpp.getEventType();

            while(eventType != XmlPullParser.END_DOCUMENT) {
                if(eventType == XmlPullParser.START_TAG) {
                    if(xpp.getName().equals(str)) { //태그 이름이 str 인자값과 같은 경우
                        ret.add(xpp.nextText());
                    }
                }
                eventType = xpp.next();
            }
        } catch(Exception e) {
            //Log.e("Error", ""+e.getMessage());
        }

        return ret;
    }

    public void onClick(View arg0) {

    }
}

//리스트 뷰에 출력할 항목
class MyInfoItem {
    MyInfoItem(String aName) {
        name = aName;
    }
    String name;
}

//어댑터 클래스
class MyInfoListAdapter extends BaseAdapter {
    Context maincon;
    LayoutInflater Inflater;
    ArrayList<MyInfoItem> arSrc;
    int layout;

    public MyInfoListAdapter(Context context, int alayout, ArrayList<MyInfoItem> aarSrc) {
        maincon = context;
        Inflater = (LayoutInflater) context.getSystemService(
                Context.LAYOUT_INFLATER_SERVICE);
        arSrc = aarSrc;
        layout = alayout;
    }

    public int getCount() {
        return arSrc.size();
    }

    public String getItem(int position) {
        return arSrc.get(position).name;
    }

    public long getItemId(int position) {
        return position;
    }

    // 각 항목의 뷰 생성
    public View getView(int position, View convertView, ViewGroup parent) {
        final int pos = position;
        if (convertView == null) {
            convertView = Inflater.inflate(layout, parent, false);
        }
        TextView txt = (TextView) convertView.findViewById(R.id.text);
        txt.setText(arSrc.get(position).name);

        return convertView;
    }
}