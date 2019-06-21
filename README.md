# Stock-Portfolio
This project is a simulated online stock portfolio management system. Users can deposit
money and use that money to purchase stocks. Users can also withdraw money and check their
balance. The money deposited is kept separate from the actual stock portfolio, and therefore
does not factor into the portfolio value. Users can only buy stocks from the Dow 30 and Nifty 50,
and these purchases are always done on January 17th, 2017 (using stock prices from that
date). Users sell stocks at the current-date’s stock prices, however, so they can see the returns
as though they had actually purchased the stocks on January 17th and sold them today. When
viewing their portfolio online, users will be viewing the current stock prices, not the January 17th
prices. Users can also download a csv file representing their stock portfolio. All transactions are
recorded and the user can review them online. They are dated and timestamped. The
buy-stocks transactions are dated 1/17/2017, while the sell-stocks transactions (and cash
deposits/withdrawals) are all dated with the current, actual transaction date. The user can only
purchase a maximum of 10 different stocks - a maximum of 7 domestic stocks and a maximum
of 3 foreign stocks. After having purchased 7 or more different stocks, users cannot sell to less
than 7 - they are limited to a minimum of 7 different stocks. New users can create an account.
The admin account can also create user accounts. Each user account can have one stock
portfolio. A user can also delete his/her account, and the admin account can delete any user
account.
Finally, users can choose to have their portfolio’s optimized. The optimization algorithm
is based on Equal Risk Contribution. It will buy and sell shares of the various stocks the user
currently owns, but will never sell all shares of any one stock. It optimizes the domestic stocks
and foreign stocks separately, as though they were in two separate portfolio.The optimization
function buys and sells stocks at the current date (and these transactions are recorded, and
stamped with the current date). Users can download a csv report on the optimization, with
before-and-after data, including the risk contributions of each stock and the portfolio beta and
returns. The risk contributions of the optimized portfolio will not be exactly equal, because that
would require the buying and selling of fractional shares of each stock. We round the number of
shares that must be bought and sold to the nearest integer. This algorithm is more effective, and
its benefits are more apparent, when dealing with relatively large amounts of shares. The
returns, risks, and betas are calculated using two years worth of daily historical stock price data.
The indices used for beta calculations are the Dow 30 (for the domestic, Dow 30 stocks) and the
Nifty 50 (for the foreign, Nifty 50 stocks). While the portfolio beta and portfolio returns aren’t
used in the optimization function, they are still calculated and presented to the user as they are
important metrics to observe in any portfolio.
The risk contribution of each stock is its risk multiplied by its portfolio weight. Since we
optimize domestic and foreign stocks separately, the risk contributions presented are risk
multiplied by domestic portfolio weight and risk multiplied by foreign portfolio weight, for
domestic and foreign stocks, respectively. The function attempts to perform optimization with as
little change in overall portfolio value as possible. In other words, the system of equations used
to calculate the amount of shares of each stock that must be bought/sold includes this equation:
0=d(S 1 )*V 1 +d(S 2 )*V 2 +...+d(S n )V n
where S 1 is the amount of shares of stock 1, d(S 1 ) is the change in amount of shares of stock 1,
and V 1 is the share value of stock 1. The other equation that makes up this system of equations
is this:
(d(S 1 )+S 1 )*R 1 *V 1 =(d(S n )+S n )*R n *V n
where R 1 is the risk of stock 1. This equation is derived from the fact that the risk contributions of
each stock must be equalized. With this system of equations, we solve for all d(S n ), round them
to the nearest integers, and optimize the portfolio.
