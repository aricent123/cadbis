package cadbis.db;

import java.sql.ResultSet;
import java.sql.SQLException;

import cadbis.bl.User;
import cadbis.bl.UserStats;
import cadbis.utils.DateUtils;

public class UserDAO extends AbstractDAO<User> {

	public UserDAO()
	{
		super(DBConnection.getInstance(), "users");
	}
	
	
	protected UserStats getStatsByLastDays(User user, Integer days)
	{
		UserStats res = null;
		ResultSet rs = null;
		String clause;
		try
		{
			if(days == 0)
				clause = "a.start_time > ";
			else
				clause = "UNIX_TIMESTAMP(a.start_time) > UNIX_TIMESTAMP(NOW()) -"; 
		   rs = getResultSet(String.format("select sum(a.in_bytes) as traffic,sum(a.time_on) as time_on " +
		   		"from `actions` a " +
		   		"where a.user = '%s' and "+clause+" %d*3600*24",user.getUser(),days));
		   if(rs!=null){
			   rs.next ();
			   res = new UserStats(rs.getLong("traffic"),rs.getLong("time_on"));
		   }
		}
		catch(SQLException e)
		{
			logger.error("Query execution error: " + e.getMessage());
		}
		finally
		{
			closeRs(rs);
		}		
		return res;
	}
	

	public User getByLoginWithStats(String login)
	{
		User usr = getItemByQuery(String.format("select u.* from `users` u where u.user='%s'",login));
		if(usr != null)
		{
			usr.setDstats(getStatsByLastDays(usr, 1));
			usr.setMstats(getStatsByLastDays(usr, DateUtils.getDOM()));
			usr.setWstats(getStatsByLastDays(usr, DateUtils.getDOW()));
			usr.setTstats(getStatsByLastDays(usr, 0));			
		}
		return usr;
	}
	
	public User getByLogin(String login)
	{
		return getItemByQuery(String.format("select u.* from `users` u where u.user='%s'",login));
	}	
	
	public boolean isUserExists(String login)
	{
		return getCountByQuery(String.format("select count(*) as count from users where user='%s'", login), "count")==1; 
	}
	
}
